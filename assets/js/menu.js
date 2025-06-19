'use strict';
/*jslint maxlen:120 */

//
// Variables
//
const body = document.querySelector( 'body' ),
    headerScrolled = 'site-header--scrolled';


//
// Hambuger elements
//
const hamburgerMenuVisible = 'site-header__hamburger-menu--visible',
    hamburgerMenuIcon = document.querySelector( '.js-show-hamburger' ),
    hamburgerMenu = document.querySelector( '.main__nav' ),
    hamburgerActive = 'site-header__icon--hamburger--active';

const siteHeader = document.querySelector( '.site-header' ),
    servicesSubmenuIcons = document.querySelectorAll( '.js-show-services' ),
    servicesSubmenuIconsDesktop = document.querySelectorAll( '.js-show-services-desktop' ),
    servicesActive = 'show-services--active',
    submenuOpen = 'site-header--submenu-open';

const menuItem = document.querySelectorAll('.js-show-services-desktop');
const menuArrow = document.querySelectorAll('.js-show-services');
let mobileViewHandler;

//
// Methods
//
const siteHeaderScroll = () => {
    const y = window.scrollY;
    if (y >= 100) {
        siteHeader.classList.add( headerScrolled );
    }else {
        siteHeader.classList.remove( headerScrolled );
    }
};

// Search bar
const searchBtn =  document.querySelector('.site-header__right--search');
const searchForm = document.querySelector('.site-header__search-bar--desktop');
const searchInput = document.querySelector('.site-header__search-bar--desktop input');

// Desktop search bar toggle
const toggleSearchForm = () => {

    if (searchBtn) searchBtn.addEventListener('click', e => {
        e.preventDefault();

        if (!searchBtn.classList.contains('open')) {
            searchBtn.classList.add('open');
            searchForm.classList.add('visible');
            siteHeader.classList.add( submenuOpen );
            document.querySelectorAll('.menu__item').forEach(element => {
                element.classList.remove( 'menu__item--visible' );
            });
            servicesSubmenuIcons.forEach( servicesSubmenuIcon => {
                servicesSubmenuIcon.classList.remove( servicesActive );
            });

            setTimeout(function() {
                searchInput.focus();
            }, 200);
        } else {
            searchBtn.classList.remove('open');
            searchForm.classList.remove('visible');
            siteHeader.classList.remove( submenuOpen );
        }
    });
};

const showHamburgerMenu = () => {
    const servicesSubmenus = document.querySelectorAll( '.submenu__container' );
    hamburgerMenuIcon.classList.toggle( hamburgerActive );
    if ( hamburgerMenuIcon.classList.contains( hamburgerActive ) ) {
        siteHeader.classList.add( submenuOpen );
        hamburgerMenu.classList.add( hamburgerMenuVisible );
        body.classList.add( 'scrolled-locked-mobile' );
    } else {
        siteHeader.classList.remove( submenuOpen );
        hamburgerMenu.classList.remove( hamburgerMenuVisible );
        body.classList.remove( 'scrolled-locked-mobile' );
    }

    if ( servicesSubmenuIcons ) servicesSubmenuIcons.forEach( servicesSubmenuIcon => {
        servicesSubmenuIcon.classList.remove( servicesActive );
    });
    if ( servicesSubmenus ) servicesSubmenus.forEach( servicesSubmenu => {
        servicesSubmenu.classList.remove( 'services-submenu--visible' );
    });
};

const showServicesSubmenu = ( e ) => {
    e.preventDefault();
    
    const windowWidth = window.innerWidth;
    const thisEl = e.target;
    
    const listItem = thisEl.closest('.menu__item');
    let subMenu = Array.from(listItem.children).filter(function (item) {
        return item.matches('.submenu__container');
    });
    const activeSubMenus = document.querySelectorAll('.menu__item--visible');

    let showIcon = Array.from(listItem.children).filter(function (item) {
        return item.matches('.js-show-services');
    });
    if ( subMenu ) {
        subMenu = subMenu[0];
        showIcon = showIcon[0];

        if( !listItem.classList.contains('menu__item--visible') ) {
            setTimeout( () => {
                listItem.classList.add('menu__item--visible');
                if (windowWidth > 1100) {
                    document.querySelectorAll('.menu__item').forEach.call(activeSubMenus, function(el) {
                        setTimeout( () => {
                            el.classList.remove('menu__item--visible');
                        }, 10);
                    });
                    if (listItem.classList.contains('menu__item--subitems')) {
                        siteHeader.classList.add( submenuOpen );
                    }
                    else {
                        siteHeader.classList.remove( submenuOpen );
                    }
                }
                searchBtn.classList.remove('open');
                searchForm.classList.remove('visible');
            }, 10);
        }
        
        if (windowWidth <= 1100) {
            [].forEach.call(activeSubMenus, function(el) {
                setTimeout( () => {
                    el.classList.remove('menu__item--visible');
                    siteHeader.classList.remove( submenuOpen );
                }, 10);
            });
        }
    }
    else {
        setTimeout( () => {
            listItem.classList.add('menu__item--visible');
            searchBtn.classList.remove('open');
            searchForm.classList.remove('visible');
            siteHeader.classList.add( submenuOpen );
        }, 10);
    }

    if (windowWidth > 1100) {
        const currentActiveSubmenu = document.querySelector('.' + servicesActive);

        hamburgerMenuIcon.classList.remove( hamburgerActive );
        hamburgerMenu.classList.remove( hamburgerMenuVisible );
    }
    
};

const prepareForMobileView = () => {
    
    const windowWidth = window.innerWidth;
    if (windowWidth <= 1100) {
        if (!mobileViewHandler) {
            mobileViewHandler = 1;
            
        }
    }else{
        if (mobileViewHandler) {
            mobileViewHandler = 0;
        }
    }
};

//
// Inits & Event Listeners
//
window.addEventListener( 'DOMContentLoaded', siteHeaderScroll );
window.addEventListener( 'scroll', siteHeaderScroll, { passive: true } );
if( hamburgerMenuIcon ) hamburgerMenuIcon.addEventListener( 'click', showHamburgerMenu );
if ( menuArrow ) menuArrow.forEach( servicesSubmenuIcon => {
    servicesSubmenuIcon.addEventListener( 'click', showServicesSubmenu );
});
if ( menuItem ) menuItem.forEach( servicesSubmenuIcon => {
    if (window.innerWidth > 1100) {
        servicesSubmenuIcon.addEventListener( 'mouseover', showServicesSubmenu );
    }
});
if (window.innerWidth > 1100) {
    toggleSearchForm();
}
window.addEventListener( 'DOMContentLoaded', prepareForMobileView );
window.addEventListener( 'resize', prepareForMobileView );


document.querySelector('.site-header').addEventListener("mouseleave", (event) => {

    if (window.innerWidth > 1100) {
        document.querySelectorAll('.menu__item').forEach(element => {
            element.classList.remove( 'menu__item--visible' );
            siteHeader.classList.remove( submenuOpen );
        });
    }
});

// Workshops booking button
const bodyElement = document.querySelector('body');
if(bodyElement.classList.contains('single-workshops')) {
    const workshopHero = document.querySelector('.workshop-hero .wh__info-card');
    const bookingBtn = document.querySelector('.site-header__right .btnBook');

    const checkIfHeroIsInView = () => {
        let elementPosition = workshopHero.getBoundingClientRect();
        let topReset = 85;
        if(window.innerWidth > 1100) {
            topReset = 50;
        }

        if(-elementPosition.height < elementPosition.top - topReset) {
            return true;
        } else {
            return false;
        }
    };

    const showOrHideBookingBtn = () => {
        let hideBtn = checkIfHeroIsInView();
        if(hideBtn) {
            bookingBtn.classList.remove('is-visible');
        } else {
            bookingBtn.classList.add('is-visible');
        }
    };
    window.addEventListener( 'scroll', showOrHideBookingBtn, { passive: true } );
}

