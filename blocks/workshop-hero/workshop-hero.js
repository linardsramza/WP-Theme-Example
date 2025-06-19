/*jslint maxlen:100 */
/* globals google  */

'use strict';

// Map functionality

// Import google API
/* jshint ignore:start */
(g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries",[...r]+"");for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.${c}apis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})({
    key: "AIzaSyAvwkrVxHfz_oCuPPxmek1QL9lP0NRYn4U",
});
/* jshint ignore:end */

async function initMap() {
    //@ts-ignore
    const { Map } = await google.maps.importLibrary("maps");

    const mapElement = document.querySelector('#map');
    const pinIcon = '/wp-content/themes/theme/dist/img/pin.svg';
    const lat = mapElement.dataset.latitude;
    const lng = mapElement.dataset.longitude;
    const latLng = { lat: parseFloat(lat), lng: parseFloat(lng) };

    // Create map
    let mapOptions = {
        center: latLng,
        zoom: 13,
        disableDefaultUI: true,
    };
    let map = new google.maps.Map(mapElement, mapOptions);

    const marker = new google.maps.Marker({
        position: latLng,
        icon: pinIcon
    });
    marker.setMap(map);
}
initMap();

// Hours accordion
const hoursAccordion = () => {
    const accordionWrapper = document.querySelector('.wh__hours');
    const accordionHeader = accordionWrapper.querySelector('.wh__hours--header');
    const accordionContent = accordionWrapper.querySelector('.wh__hours--wrappers');

    const openAccordion = () => {
        let wrapperHeight = accordionContent.querySelector('ul').clientHeight + 22;
        accordionWrapper.classList.add('opened');
        accordionWrapper.classList.remove('closed');
        accordionContent.style.height = wrapperHeight + 'px';
    };

    const closeAccordion = () => {
        accordionWrapper.classList.remove('opened');
        accordionWrapper.classList.add('closed');
        accordionContent.style.height = '0';
    };

    accordionHeader.addEventListener('click', function() {
        if(accordionWrapper.classList.contains('closed')) {
            openAccordion();
        } else {
            closeAccordion();
        }
    });

    window.addEventListener('resize', closeAccordion);

    if(window.innerWidth > 992) {
        openAccordion();
    } else {
        closeAccordion();
    }
};
hoursAccordion();