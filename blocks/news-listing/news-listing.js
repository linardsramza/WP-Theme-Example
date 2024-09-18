'use strict';

var dropDownValue = document.getElementById('categories-select');

dropDownValue.addEventListener('change', function() {
	if (this.selectedIndex !== 0) {
		window.location.href = this.value;
	}
});