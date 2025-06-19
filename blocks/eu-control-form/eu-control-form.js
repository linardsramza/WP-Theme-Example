/* globals euControlForm */
/*jslint maxlen:170 */

'use strict';

const form = document.querySelector('#registration-number-form');

if(form) {
	form.addEventListener('submit', (event) => {
		event.preventDefault();

		let inputValue = form.querySelector('#registration-number').value;
		let formattedValue = inputValue.replace(' ', ''); //remove all gaps
		let requestURL = euControlForm.restbase + 'wp-json/theme/v1/eu-control/' + formattedValue;

		if(inputValue !== '') {

			let registrationInfo = document.querySelector('.eu-control-form__registration-info');
			let message = registrationInfo.querySelector('.eu-control-form__message');
			let responseEl = message.querySelector('.response');

			responseEl.innerHTML = '<div>' + euControlForm.loading_message + '</div>';
			registrationInfo.style.maxHeight = message.offsetHeight + 24 + 'px';

			fetch( requestURL, {
		        method: 'GET',
		        headers: {
		            'Content-Type': 'application/json'
		        }
		    } ).then( res => {
		        return res.json();
		    }).then( res => {
		    	let response = JSON.parse(res);
		    	responseEl.innerHTML = '';
		    	if(response && response.success) {
			        responseEl.innerHTML += '<div>' + response.success.Merke + ' (' + response.success['Reg. nummer'] + ')</div>';
			        responseEl.innerHTML += '<div><b>'+ euControlForm.control_deadline_str + ': ' + response.success['Frist neste godkjenning'] + '</b></div>';

			        registrationInfo.style.maxHeight = message.offsetHeight + 24 + 'px';
			    } else if(response == null || response.error) {
			    	responseEl.innerHTML += '<div>' + euControlForm.error_message + '</div>';

			    	registrationInfo.style.maxHeight = message.offsetHeight + 24 + 'px';
			    }
		    });
		}
	});
}