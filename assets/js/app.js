'use strict';

const siteFunctions = (function() {
	
	const testFunc = () => {
		console.log( 'Test init!' );
		const multiplyES6 = (x, y) => { return x * y; };
		console.log( multiplyES6 );
	};

	let publicFunctions = {};
	publicFunctions.init = function(options) {
		testFunc();
	};

	return publicFunctions;
})();

siteFunctions.init();


const windowWidth = window.innerWidth;

// Accordion creation
function createAccordion (accordionClass, isTitleClickable = false) {

    let accordion = document.querySelectorAll(accordionClass + ' .accordion__item');

    accordion.forEach((accordion_item) => {
        let title = '';
        
        if (isTitleClickable) {
            title = accordion_item.querySelector('.accordion__title--toggle');
        }
        else {
            title = accordion_item.querySelector( '.accordion__title' );
        }
    
        if (document.querySelector('.accordion__more')) {
            const loadMore = document.querySelector('.accordion__more');
    
            loadMore.addEventListener('click', function () {
                accordion_item.classList.remove('accordion__item--hidden');
                loadMore.style.display = 'none';
            });
        }

        title.addEventListener('click', function () {
            accordion.forEach((this_item) => {
                const description = this_item.querySelector( '.accordion__description' );
                if ( this_item === accordion_item ) {
    
                    if ( !this_item.classList.contains( 'is-active' ) ) {
                        this_item.classList.add( 'is-active' );
                        description.style.height = 'auto';
                        const height = description.offsetHeight;
                        description.style.height = '0';
                        setTimeout(() => {
                            description.style.height = height + 'px';
                        }, 10);
                    } else{
                        this_item.classList.remove( 'is-active' );
                        description.style.height = '0';
                    }
    
                } else {
                    this_item.classList.remove( 'is-active' );
                    description.style.height = '0px';
                }
            });
    
        });
    });
}

createAccordion('.accordion', false);

if (windowWidth <= 768) {
    createAccordion('.menu-list__accordion', true);
}

// window.addEventListener( 'resize', prepareForMobileView );