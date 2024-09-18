'use strict';

const videoBlocks = document.querySelectorAll('.block.video');

videoBlocks.forEach((block) => {
	let playIcon = block.querySelector('.video__play-icon');
	let videoThumbnail = block.querySelector('.video__thumbnail');

	function closeThumbnail() {
		var iframe = block.querySelector('iframe');
    	iframe.contentWindow.postMessage('{"event":"command","func":"playVideo","args":""}', '*');

		playIcon.style.display = 'none';
		videoThumbnail.style.display = 'none';
	}

	if(playIcon) {
		playIcon.addEventListener('click', () => {
			closeThumbnail();
		});
	}
	if(videoThumbnail) {
		videoThumbnail.addEventListener('click', () => {
			closeThumbnail();
		});
	}
});
