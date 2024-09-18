'use strict';

const btn = document.querySelector('.services__show-more-btn');
const services = document.querySelectorAll('.services__list .service__column');

if(btn) {
	btn.addEventListener('click', (e) => {
		e.preventDefault();

		services.forEach((item) => {
			item.style.display = 'block';
		});
		btn.style.display = 'none';
	});
}