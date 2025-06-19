/* globals Swiper */
'use strict';

var init = false;
let mobile = window.matchMedia('(max-width: 1200px)');
let swiper = '';

function swiperCard() {
    if (mobile.matches) {
        if (!init) {
            init = true;
            let swiper = new Swiper(".testimonials__slider", {
            breakpoints: {
                0: {
                    slidesPerView: 1.2,
                    spaceBetween: 12
                },
                480: {
                    slidesPerView: 1.2,
                    spaceBetween: 12
                },
                768: {
                    slidesPerView: 2.5,
                    spaceBetween: 12
                },
            },
            });
        }
    } else if (init) {
        swiper.destroy();
        init = false;
    }
}

window.addEventListener("load", function () {
    swiperCard();
});
window.addEventListener("resize", swiperCard);