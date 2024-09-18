/* globals Swiper */

'use strict';

// Load more reviews
const loadMoreBtn = document.querySelector('.workshop-reviews__load-more button');
const allReviews = document.querySelectorAll('.workshop-reviews__card');

if(loadMoreBtn) {
    const showAllReviews = () => {
        allReviews.forEach(review => {
            review.style.display = 'block';
        });
        loadMoreBtn.style.display = 'none';
    };

    loadMoreBtn.addEventListener('click', showAllReviews);
}

// Reviews slider
const swiper = new Swiper('.swiper', {
    slidesPerView: 1.3,
    spaceBetween: 20,
    loop: true,
    autoHeight: true,
    breakpoints: {
        576: {
            slidesPerView: 2.3,
        },
        768: {
            slidesPerView: 3.3,
        },
    },
});
