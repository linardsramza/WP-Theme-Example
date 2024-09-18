'use strict';

const showAllServices = () => {
    const services = document.querySelectorAll('.workshop-description__list li');
    const showAllBtn = document.querySelector('.workshop-description__show-all');
    if(showAllBtn) {
        showAllBtn.addEventListener('click', function() {
            services.forEach(service => {
                service.style.display = 'block';
            });
            showAllBtn.style.display = 'none';
        });
    }
};

showAllServices();