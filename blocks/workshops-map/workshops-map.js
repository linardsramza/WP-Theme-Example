/*jslint maxlen:150 */
/* globals google, MarkerClusterer, restBase, Choices */
'use strict';
const mapFunctions = (function () {
    // Map Variables
    const mapElement = document.querySelector('#map');
    const country = mapElement.dataset.country;
    const mapCenterLat = parseInt(mapElement.dataset.latitude);
    const mapCenterLong = parseInt(mapElement.dataset.longitude);
    let map;
    let markers;
    let markerCluster;
    let infoWindow;
    let geocoder;

    // Map search radius and zoom levels
    let baseZoomLevel = 5;
    let defaultLocationZoomLvl = 10;
    let defaultLocationSearchRadius = 50;
    let customLocationSettings;

    // Workshop List variables
    const workshopWrapper = document.querySelector('#workshops');
    let workshopList;

    // Filters variables
    const getLocationBtn = document.querySelector('#get-location');
    const filterForm = document.querySelector('#workshop_filters');
    const zipCodeField = filterForm.querySelector('#search-field');
    const zipCodeBtn = filterForm.querySelector('#zip-code-search-btn');
    const searchByLocationField = filterForm.querySelector('#search-by-location');
    const filterDropdowns = filterForm.querySelectorAll('.filter-dropdown');
    const activeFiltersElement = document.querySelector('.workshops-map__filters--selected-items');
    const removeFilterBtn = activeFiltersElement.querySelector('#clear-all-filters');
    const changeOrderSelect = document.querySelector('select[name=order-form]');
    const noWorkshopsFoundTitle = workshopWrapper.querySelector('.no-workshops-found');
    let changeOrderSelectChoices;
    let locationBasedOnZipCode = false;
    let searchPhrase;
    let zipCodeLat;
    let zipCodeLong;
    let userLat;
    let userLong;

    // Mobile view element varaibles
    const removeFilterBtnMobile = document.querySelector('#mobile-clear-filter');
    const showMapBtn = document.querySelector('#show-map');
    const showFiltersBtn = document.querySelector('#mobile-show-filters');
    const hideFiltersBtn = document.querySelector('#mobile-close-filter');
    const filterWrapper = filterForm.querySelector('.workshops-map__filters--wrapper');
    const hideFiltersBtnCounter = document.querySelector('#mobile-close-filter .count');
    const loadMoreBtn = document.querySelector('#load-more-workshops');
    const workshopCountText = document.querySelector('.workshop-count-info');
    let workshopOrder = 1;
    let workshopCount;
    let currentPage = 1;
    let visibleWorkshopCount;

    // Set geo location variables
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const findgeo = urlParams.get('findgeo');

    // ================
    // HELPER FUNCTIONS
    // ================

    // Opens workshop info card
    const openWorkshopInfoCard = (i) => {
        workshopList[i].classList.add('opened');
        const elementPosition = workshopList[i].offsetTop - workshopWrapper.offsetTop;
        workshopWrapper.scroll({
            top: elementPosition,
            behavior: 'smooth'
        });
    };

    // Calculates distance between user location and workshop
    const  calculateDistance = (workshopLat, workshopLang, locationType) => {
        let lat;
        let lang;

        if(locationType === 'byZipCode') {
            lat = zipCodeLat;
            lang = zipCodeLong;
        } else {
            lat = userLat;
            lang = userLong;
        }

        const radlat1 = Math.PI * workshopLat / 180;
        const radlat2 = Math.PI * lat / 180;
        const theta = workshopLang - lang;
        const radtheta = Math.PI * theta/180;
        let dist = Math.sin(radlat1) * Math.sin(radlat2) + Math.cos(radlat1) * Math.cos(radlat2) * Math.cos(radtheta);
        if (dist > 1) {
            dist = 1;
        }
        dist = Math.acos(dist);
        dist = dist * 180/Math.PI;
        dist = dist * 60 * 1.1515;
        dist = dist * 1.609344;
        return dist;
    };

    // ================
    // GEO FUNCTIONALITY
    // ================

    const activateGeoLocation = () => {
        if (findgeo == 'true') {
            document.getElementById('get-location').click();
        }
    };

    // =================
    // SET VALUES
    // =================

    // Get location search default values
    const getLocationSearchSettings = () => {
        // Get all workshops locations
        let requestURL = restBase.restbase + 'wp-json/theme/v1/workshops-map-settings';
        fetch( requestURL, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        }).then( res => {
            return res.json();
        }).then( res => {
            if(res.default_settings.search_radius) {
                defaultLocationSearchRadius = res.default_settings.search_radius;
            }
            if(res.default_settings.zoom_level) {
                defaultLocationZoomLvl = res.default_settings.zoom_level;
            }
            if(res.location_settings) {
                customLocationSettings = res.location_settings;
            }
        });
    };

    // =================
    // MAP FUNCTIONALITY
    // =================

    // Create map

    // Import google API

    /* jshint ignore:start */
    (g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries",[...r]+"");for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.${c}apis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})({
        key: "AIzaSyAvwkrVxHfz_oCuPPxmek1QL9lP0NRYn4U",
    });
    /* jshint ignore:end */

    const initMap =  async (locations, locationInfo) => {
        const { Map } = await google.maps.importLibrary("maps");
        geocoder = new google.maps.Geocoder();

        const pinIcon = '/wp-content/themes/theme/dist/img/pin.svg';
        const clusterIcon = '/wp-content/themes/theme/dist/img/cluster.svg';

        // Create map
        if(window.matchMedia('(max-width: 768px)').matches) {
            baseZoomLevel = 3.9;
        } else if(window.matchMedia('(max-width: 992px)')) {
            baseZoomLevel = 4.8;
        }

        map = new Map(mapElement, {
            center: { lat: Number(mapCenterLat), lng: Number(mapCenterLong)},
            zoom: baseZoomLevel,
        });

        // Set workshop pins
        infoWindow = new google.maps.InfoWindow();
        markers = locations.map((position, i) => {
            const info = locationInfo[i];
            const marker = new google.maps.Marker({position, map, icon: pinIcon, id: i, content: info});

            marker.addListener('click', () => {
                infoWindow.close();
                infoWindow.setContent(marker.content);
                infoWindow.open(marker.map, marker);
                openWorkshopInfoCard(i);
            });

            return marker;
        });

        // Set map cluster
        const mcOptions = {styles: [{
            fontFamily: 'Gotham',
            fontWeight: 400,
            textColor: '#fff',
            textLineHeight: 62,
            textSize: 14,
            height: 62,
            width: 62,
            url: clusterIcon,
        }]};
        markerCluster = new MarkerClusterer(map, markers, mcOptions);
        markerCluster.setIgnoreHidden(true);
    };

    // Show map
    function showWorkshopsMap() {
        // Get all workshops locations
        let requestURL = restBase.restbase + 'wp-json/theme/v1/workshops-locations';
        fetch( requestURL, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        }).then( res => {
            return res.json();
        }).then( res => {
            const locations = res.locations;
            const locationInfo = res.info;
            initMap(locations, locationInfo);
        });
    }

    const updateMapMarkers = (markersToShow, markersToHide) => {
        markersToShow.forEach(show => {
            markers[show].setVisible(true);
        });

        markersToHide.forEach(hide => {
            markers[hide].setVisible(false);
        });
        markerCluster.repaint();
    };

    const centerMapPositionToBase = () => {
        if(map.center.lat() !== mapCenterLat || map.center.lng() !== mapCenterLong) {
            map.setCenter({lat: mapCenterLat, lng: mapCenterLong});
            map.setZoom(baseZoomLevel);
        }
    };

    const centerMapPosition = () => {
        map.setCenter({lat: userLat, lng: userLong});
        map.setZoom(12);
    };

    const centerMapPositionToZipCode = (zoomLvl = defaultLocationZoomLvl) => {
        if(map.center.lat() !== zipCodeLat || map.center.lng() !== zipCodeLong) {
            zoomLvl = parseInt(zoomLvl);
            map.setCenter({lat: zipCodeLat, lng: zipCodeLong});
            map.setZoom(zoomLvl);
        }
    };

    const centerMapPositionToMarker = (marker) => {
        const newMapCenter = marker.getPosition();
        map.setCenter(newMapCenter);
        map.setZoom(17);
    };

    // =============
    // WORKSHOP LIST
    // =============

    // Show workshops list
    const addWorkshopsToList = (workshops) => {
        workshops.forEach(workshop => {
            workshopWrapper.insertAdjacentHTML('beforeend', workshop);
        });
        workshopList = workshopWrapper.querySelectorAll('.workshops-map__info-card');
        workshopCount = workshops.length;
    };

    const addWorkshopAccordionFunctionality = () => {
        workshopList.forEach( function(workshop, i) {
            const openCardBtn = workshop.querySelector('.open-btn');
            const closeCardBtn = workshop.querySelector('.close-btn');
            const closeCardBtnMob = workshop.querySelector('.close-btn--mobile');

            const openInfoCard = () => {
                openWorkshopInfoCard(i);
                centerMapPositionToMarker(markers[i]);
                infoWindow.close();
                infoWindow.setContent(markers[i].content);
                infoWindow.open(map, markers[i]);
            };

            const closeInfoCard = () => {
                if(workshop.classList.contains('opened')) {
                    workshop.classList.remove('opened');
                }
                infoWindow.close();
            };

            openCardBtn.addEventListener('click', openInfoCard);
            closeCardBtn.addEventListener('click', closeInfoCard);
            closeCardBtnMob.addEventListener('click', closeInfoCard);
        });
    };

    const showWorkshopsList = () => {
        // Get all workshops locations
        let requestURL = restBase.restbase + 'wp-json/theme/v1/workshop-list';
        fetch( requestURL, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        }).then( res => {
            return res.json();
        }).then( res => {
            const workshops = res.workshop_cards;
            addWorkshopsToList(workshops);
            addWorkshopAccordionFunctionality();
            filtering(); // Init workshop filtering
            initLoadMoreFunction(); // Init load more function for workshops
            initServicesAccardion(); // Init services accordion
        });
    };


    // ====================
    // FILTER FUNCTIONALITY
    // ====================

    // Show/hide check boxes
    const showOrHideFilterDropDowns = () => {

        const closeActiveDropDown = () => {
            const activeDropdown = filterForm.querySelector('.filter-dropdown.active');
            if(activeDropdown) {
                activeDropdown.classList.remove('active');
                window.removeEventListener("click", closeActiveDropDown);
            }
        };

        const closeDropDown = (e) => {
            const activeDropdown = filterForm.querySelector('.filter-dropdown.active');
            if(e.target !== activeDropdown && ! activeDropdown.contains(e.target)) {
                closeActiveDropDown();
                window.removeEventListener("click", closeDropDown);
            }
        };

        filterDropdowns.forEach(dropdown => {
            let dropDownToggle = dropdown.querySelector('.filter-btn');
            dropDownToggle.addEventListener('click', function () {
                if(!dropdown.classList.contains('active')) {
                    closeActiveDropDown();
                    dropdown.classList.add('active');
                    window.addEventListener("click", closeDropDown);
                } else {
                    dropdown.classList.remove('active');
                    window.removeEventListener("click", closeDropDown);
                }
            });
        });
    };

    // Search in filters
    const searchInFilterDropDowns = () => {
        filterDropdowns.forEach(dropdown => {
            const searchInput = dropdown.querySelector('.workshops-map__filters--search');
            if(searchInput) {
                searchInput.addEventListener('keyup', function() {
                    const searchPhase = searchInput.value.toUpperCase();
                    const li = dropdown.querySelectorAll('li');
                    for (let i = 1; i < li.length; i++) {
                        let label = li[i].querySelector('label');
                        let txtValue = label.textContent || label.innerText;
                        if (txtValue.toUpperCase().indexOf(searchPhase) > -1) {
                            li[i].style.display = '';
                        } else {
                            li[i].style.display = 'none';
                        }
                    }
                });
            }
        });
    };

    // Filter workshops
    const filtering = async () => {

        const removeFilter = (filter) => {
            const id = filter.dataset.id;
            const checkbox = document.querySelector('input[value="'+ id +'"]');
            checkbox.checked = false;
        };

        const removeOneFilter = (e) => {
            const targetElement = e.target.parentElement;
            removeFilter(targetElement);
            workShopFilter();
        };

        const removeAllFilter = () => {
            event.preventDefault();
            const activeFilter = activeFiltersElement.querySelectorAll('span[data-id]');
            activeFilter.forEach(filter => {
                removeFilter(filter);
            });
            zipCodeField.value = '';
            searchPhrase = '';
            workShopFilter();
        };

        const addActiveFilterTags = (selectedValues) => {
            let tagsHtml = '';
            selectedValues.forEach(value => {
                let selectedElement = document.querySelector('input[value="'+ value +'"]').nextElementSibling.innerText;
                tagsHtml += '<span data-id="'+ value +'">'+ selectedElement +'<i class="fa-solid fa-xmark"></i></span>';
            });
            activeFiltersElement.style.display = tagsHtml !== '' ? 'flex' : 'none';
            tagsHtml += removeFilterBtn.outerHTML;
            activeFiltersElement.innerHTML = tagsHtml;
            const newActiveFilters = activeFiltersElement.querySelectorAll('span i');
            newActiveFilters.forEach(filter => {
                filter.addEventListener('click', removeOneFilter);
            });
            activeFiltersElement.querySelector('#clear-all-filters').addEventListener('click', removeAllFilter);
        };

        const centerMapToCity = (activeCityId) => {
            const activeCityElement = document.querySelector('input[value="'+ activeCityId +'"]');
            const activeCityName = activeCityElement.nextElementSibling.innerText;
            setZipCodeLocation(activeCityName, false);
        };

        const showHideWorkshops = (byLocation, services, cities, workingHours, certificates) => {
            const locationWorkshopLength = byLocation.length;
            const servicesLength = services.length;
            const workshopCities = cities.length;
            const workingHoursLength = workingHours.length;
            const certificatesLength = certificates.length;
            const workshopsToHide = [];
            const workshopsToShow = [];
            workshopList.forEach(function callback(workshop, i) {

                // Location workshops filter
                let locationWorkshopsFilter = true;
                if(locationWorkshopLength > 0) {
                    let isWorkshopClose = workshop.dataset.closeToLocation;
                    locationWorkshopsFilter = isWorkshopClose == 1;
                }

                // Services filter
                let servicesFilter = true;
                if(servicesLength > 0) {
                    let workshopServices = workshop.dataset.services;
                    workshopServices = workshopServices.split(',');
                    servicesFilter = (services.filter(service => workshopServices.includes(service)).length > 0);
                }

                // Cities filter
                const workshopCity = workshop.dataset.city;
                let citiesFilter = true;
                if(workshopCities > 0) {
                    citiesFilter = (cities.includes(workshopCity));
                }

                // Filtering opening hours
                let workingHoursFilter = true;
                if(workingHoursLength > 0) {
                    if(workingHours.includes('open-evenings')) {
                        const openEvenings = workshop.dataset.openEvening;
                        workingHoursFilter = openEvenings > 0;
                    } else if(workingHours.includes('open-weekends')) {
                        const openWeekends = workshop.dataset.openWeekends;
                        workingHoursFilter = openWeekends > 0;
                    } else if(workingHours.includes('open-now')) {
                        var startTime = workshop.dataset.openToday;
                        var endTime = workshop.dataset.closesToday;
                        let currentDate = new Date();
                        let startDate = new Date(currentDate.getTime());
                        startDate.setHours(startTime.split(":")[0]);
                        startDate.setMinutes(startTime.split(":")[1]);

                        let endDate = new Date(currentDate.getTime());
                        endDate.setHours(endTime.split(":")[0]);
                        endDate.setMinutes(endTime.split(":")[1]);
                        workingHoursFilter = startDate < currentDate && endDate > currentDate;
                    }
                }

                // Filter Certificates
                let certificateFilter = true;
                if(certificatesLength > 0) {
                    let workshopCertificates = workshop.dataset.certificates;
                    workshopCertificates = workshopCertificates.split(',');
                    certificateFilter = (certificates.filter(certificate => workshopCertificates.includes(certificate)).length > 0);
                }

                if(locationWorkshopsFilter && servicesFilter && citiesFilter && workingHoursFilter && certificateFilter) {
                    workshop.classList.add('visible');
                    workshopsToShow.push(i);
                } else {
                    workshop.classList.remove('visible');
                    workshopsToHide.push(i);
                }
            });

            workshopCount = workshopsToShow.length;

            if(workshopsToShow.length > 0) {
                noWorkshopsFoundTitle.style.display = 'none';
            } else {
                noWorkshopsFoundTitle.style.display = 'block';
            }

            if(showHideWorkshops) {
                updateMapMarkers(workshopsToShow, workshopsToHide);
            }
        };

        // Main workshop filter function
        const workShopFilter = () => {
            let formData = new FormData(filterForm);
            let activeLocationFilter = formData.getAll('search-by-location');
            let activeServices = formData.getAll('service');
            let activeCities = formData.getAll('city');
            let activeHours = formData.getAll('opening-hours');
            let activeCertificates = formData.getAll('certificates');
            let activeFilters = activeServices.concat(activeLocationFilter, activeCities, activeHours, activeCertificates);
            addActiveFilterTags(activeFilters);
            showHideWorkshops(activeLocationFilter, activeServices, activeCities, activeHours, activeCertificates);

            if((activeLocationFilter.length === 0 && activeCities.length !== 1) || (activeLocationFilter.length > 0 && activeCities.length > 0)) {
                centerMapPositionToBase();
            } else if(activeCities.length === 1) {
                centerMapToCity(activeCities[0]);
            }

            // Update mobile filter and load more function
            if(window.matchMedia('(max-width: 768px)').matches) {
                updateMobileFilter();
                showNextWorkshops(1);
            }
        };

        const showWorkshopsByCoordinates = (locationType) => {
            let notFound = true;
            let searchRadius = defaultLocationSearchRadius;
            const searchPhraseLowercase = searchPhrase.toLowerCase();
            if(customLocationSettings[searchPhraseLowercase]) {
                searchRadius = customLocationSettings[searchPhraseLowercase].search_radius;
            }
            // Filter based on coordinates
            workshopList.forEach(workshop => {
                let showWorkshop = 0;
                const workshopLat = workshop.dataset.latitude;
                const workshopLang = workshop.dataset.longitude;
                const distance = calculateDistance(workshopLat, workshopLang, locationType);
                if(distance < searchRadius) {
                    showWorkshop = "1";
                    notFound = false;
                } else {
                    showWorkshop = "0";
                }
                workshop.dataset.closeToLocation = showWorkshop;
            });
            return notFound;
        };

        const showWorkshopsByLocation = (locationType) => {
            searchByLocationField.checked = true;

            // Change workshop order by closest ones or by closest to zip code
            if(locationType === 'byZipCode') {
                locationBasedOnZipCode = true;
            } else {
                locationBasedOnZipCode = false;
            }

            showWorkshopsByCoordinates(locationType);
            workShopFilter();

            changeOrderSelectChoices.setChoiceByValue('3');
            changeOrderSelect.dispatchEvent(new Event('change'));
        };

        const setLocation = (position) => {
            userLat = position.coords.latitude;
            userLong = position.coords.longitude;
            showWorkshopsByLocation('byUserLocation');
            centerMapPosition();
        };

        const getLocationError = () => {
            console.log('Could not get the location');
        };

        const getLocation = () => {
            navigator.geolocation.getCurrentPosition(setLocation, getLocationError);
        };

        const filterClosestWorkshops = () => {
            event.preventDefault();
            if(!userLat && !userLong) {
                getLocation();
            } else {
                showWorkshopsByLocation('byUserLocation');
                centerMapPosition();
            }
        };

        const setZipCodeLocation = (locationString, doFiltering) => {
            geocoder.geocode( { 'address': locationString + ' ' + country}, function(results, status) {
                const formattedAddress = results ? results[0].formatted_address : country;
                let zoomLvl = defaultLocationZoomLvl;
                const searchPhraseLowercase = searchPhrase.toLowerCase();
                if(customLocationSettings[searchPhraseLowercase]) {
                    zoomLvl = customLocationSettings[searchPhraseLowercase].zoom;
                }
                if (formattedAddress !== country) {
                  zipCodeLat = results[0].geometry.location.lat();
                  zipCodeLong = results[0].geometry.location.lng();
                  centerMapPositionToZipCode(zoomLvl);
                } else {
                    zipCodeLat = '';
                    zipCodeLong = '';
                }
                if(doFiltering) {
                    showWorkshopsByLocation('byZipCode');
                    workShopFilter();
                }
            });
        };

        const searchWorkshopByZipCode = () => {
            if(event) {
                event.preventDefault();
            }
            const regExp = /[a-zA-Z]/g;
            searchPhrase = zipCodeField.value;

            if(searchPhrase == '') {
                zipCodeLat = '';
                zipCodeLong = '';
                locationBasedOnZipCode = false;
                searchByLocationField.checked = false;
                workShopFilter();
            } else {
                setZipCodeLocation(searchPhrase, true);
            }
        };

        filterForm.addEventListener('change', workShopFilter);
        getLocationBtn.addEventListener('click', filterClosestWorkshops);
        activateGeoLocation();
        removeFilterBtnMobile.addEventListener('click', removeAllFilter);
        zipCodeBtn.addEventListener('click', searchWorkshopByZipCode);

        // Add search bar input event lisener
        let delayTimer;
        const waitTime = 1000;
        zipCodeField.addEventListener('keyup', event => {
            clearTimeout(delayTimer);
            delayTimer = setTimeout(() => {
                searchWorkshopByZipCode();
            }, waitTime);
        });

        // Initial search filtering
        if(zipCodeField.value != '') {
            await google.maps.importLibrary("geocoding");
            searchWorkshopByZipCode();
        }
    };

    // =====================
    // CHANGE WORKSHOP ORDER
    // =====================

    const changeWorkshopOrder = () => {

        changeOrderSelectChoices = new Choices(changeOrderSelect, {
            searchEnabled: false,
            itemSelectText: '',
            shouldSort: false
        });


        const setLocation = (position) => {
            userLat = position.coords.latitude;
            userLong = position.coords.longitude;
            orderByDistance();
        };

        const getLocationError = () => {
            console.log('Could not get the location');
        };

        const getLocations = () => {
            navigator.geolocation.getCurrentPosition(setLocation, getLocationError);
        };

        const compareLocations = (location1, location2) => {
            // Order by zip code location or user location
            const compareTo = locationBasedOnZipCode ? 'byZipCode' : 'byUserLocation';
            const distance1 = calculateDistance(location1[0], location1[1], compareTo);
            const distance2 = calculateDistance(location2[0], location2[1], compareTo);
            return distance1 - distance2;
        };

        const orderByDistance = () => {
            let allWorkshopLocations = Array.from(workshopList, x => [x.dataset.latitude, x.dataset.longitude]);
            allWorkshopLocations.sort(compareLocations);
            workshopList.forEach(workshop => {
                const workshopOrder = allWorkshopLocations.findIndex(item =>
                    item[0] == workshop.dataset.latitude && item[1] == workshop.dataset.longitude
                );
                workshop.style.order = workshopOrder;
            });

            showNextWorkshops(currentPage);
        };

        const orderAlphabetically = () => {
            let allWorkshopTitles = Array.from(workshopList, x => x.querySelector('.workshops-map__info-card--header .h4').innerText);
            allWorkshopTitles.sort((a, b) => a.localeCompare(b, document.documentElement.lang));
            workshopList.forEach(workshop => {
                const workshopTitle = workshop.querySelector('.workshops-map__info-card--header .h4').innerText;
                const workshopOrder = allWorkshopTitles.indexOf(workshopTitle);
                workshop.style.order = workshopOrder;
            });
        };

        const changeOrder = () => {
            if(workshopWrapper.classList.contains('reverse-order')) {
                workshopWrapper.classList.remove('reverse-order');
            }
            if(changeOrderSelect.value == 1) {
                orderAlphabetically();
            } else if(changeOrderSelect.value == 2) {
                orderAlphabetically();
                workshopWrapper.classList.add('reverse-order');
            } else if(changeOrderSelect.value == 3) {
                if(!userLat && !userLong && !locationBasedOnZipCode) {
                    getLocations();
                } else {
                    orderByDistance();
                }
            }
            workshopWrapper.scrollTop = -workshopWrapper.scrollHeight;
            workshopOrder = changeOrderSelect.value;

            showNextWorkshops(currentPage);
        };

        changeOrderSelect.addEventListener('change', changeOrder);
    };

    // =====================
    // MOBILE VIEW FUNCTIONS
    // =====================

    // Update to workshop count text
    const updateWorkshopCountText = () => {
        workshopCountText.querySelector('.visible-workshop-count').innerText = ' ' + visibleWorkshopCount + ' ';
        workshopCountText.querySelector('.workshop-count').innerText = ' ' + workshopCount + ' ';
    };

    // Function to update mobile filter counts
    const updateMobileFilter = () => {
        const formData = new FormData(filterForm);
        const activeFilterCount = Array.from(formData.keys()).length;

        hideFiltersBtnCounter.innerText = workshopCount;

        if(activeFilterCount > 0) {
            removeFilterBtnMobile.style.display = 'block';
        } else {
            removeFilterBtnMobile.style.display = 'none';
        }

        // Add filet counts to select fields
        const selectCounters = filterForm.querySelectorAll('.filter-btn .count');

        const selectedServicesCount = formData.getAll('service').length;
        selectCounters[0].innerText = selectedServicesCount > 0 ? '(' + selectedServicesCount + ')' : '';

        const selectedCitiesCount = formData.getAll('city').length;
        selectCounters[1].innerText = selectedCitiesCount > 0 ? '(' + selectedCitiesCount + ')' : '';

        const selectedHoursCount = formData.getAll('opening-hours').length;
        selectCounters[2].innerText = selectedHoursCount > 0 ? '(' + selectedHoursCount + ')' : '';

        const selectedCertificatesCount = formData.getAll('certificates').length;
        if(selectCounters[3]) {
            selectCounters[3].innerText = selectedCertificatesCount > 0 ? '(' + selectedCertificatesCount + ')' : '';
        }
    };

    const mobileToggles = () => {
        const showMap = () => {
            event.preventDefault();
            if(!showMapBtn.classList.contains('active')) {
                showMapBtn.classList.add('active');
                mapElement.classList.add('active');
            } else {
                showMapBtn.classList.remove('active');
                mapElement.classList.remove('active');
            }
        };

        const showFilters = () => {
            event.preventDefault();
            updateMobileFilter();
            filterWrapper.classList.add('opened');
        };

        const hideFilters = () => {
            event.preventDefault();
            filterWrapper.classList.remove('opened');
        };

        showMapBtn.addEventListener('click', showMap);
        showFiltersBtn.addEventListener('click', showFilters);
        hideFiltersBtn.addEventListener('click', hideFilters);
    };

    const showNextWorkshops = (page) => {
        if(window.matchMedia('(min-width: 768px)').matches) {
            return;
        }

        let workshopCountOnPage = page * 10;
        visibleWorkshopCount = 0;

        let allVisibleWorkshops = workshopWrapper.querySelectorAll('.workshops-map__info-card.visible');
        allVisibleWorkshops = Array.from(allVisibleWorkshops);
        allVisibleWorkshops.sort((a, b) => a.style.order - b.style.order);

        if(workshopOrder == 2) {
            allVisibleWorkshops.reverse();
        }

        allVisibleWorkshops.forEach( function(workshop, i) {
            if(i < workshopCountOnPage) {
                workshop.classList.add('visible-in-current-page');
                visibleWorkshopCount++;
            } else {
                workshop.classList.remove('visible-in-current-page');
            }
        });

        if(allVisibleWorkshops.length < workshopCountOnPage) {
            loadMoreBtn.style.display = 'none';
        } else {
            loadMoreBtn.style.display = 'block';
        }

        loadMoreBtn.dataset.page = page;

        // Update workshop count text
        updateWorkshopCountText();
    };

    const initLoadMoreFunction = () => {
        const loadNextWorkshops = () => {
            currentPage = Number(loadMoreBtn.dataset.page) + 1;
            showNextWorkshops(currentPage);
        };
        loadMoreBtn.addEventListener('click', loadNextWorkshops);
        showNextWorkshops(1);
    };

    const initServicesAccardion = () => {
        const accardionTriggers = document.querySelectorAll('.services-wrap__show-all');
        accardionTriggers.forEach(accardion => {
            accardion.addEventListener('click', () => {
                const serviceList = accardion.previousSibling.previousSibling;
                serviceList.classList.add('active');
                accardion.style.display = 'none';
            });
        });
    };

    // ==================
    // TABS FUNCTIONALITY
    // ==================

    // Tabs switcher
    const tabs = document.querySelector(".tabs");
    const tabButton = document.querySelectorAll(".tabs__btn");
    const contents = document.querySelectorAll(".tabs__content--item");
    tabs.onclick = e => {
        const id = e.target.dataset.id;
        if (id) {
        tabButton.forEach(btn => {
            btn.classList.remove("active");
        });
        e.target.classList.add("active");
        contents.forEach(content => {
            content.classList.remove("active");
        });
        const element = document.getElementById(id);
        element.classList.add("active");
        }
    };

    let publicFunctions = {};
    publicFunctions.init = function (options) {
        // Some functions are initialized here, some in showWorkshopsList
        showWorkshopsList();
        showWorkshopsMap();
        getLocationSearchSettings();
        showOrHideFilterDropDowns();
        searchInFilterDropDowns();
        changeWorkshopOrder();
        mobileToggles();
        // Reload page if marketing cookies settings changes
        document.addEventListener("cmplz_status_change", function() {
            location.reload();
        });
    };

    return publicFunctions;
})();

mapFunctions.init();
