(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('backImg', backImg);

	function backImg() {

		var directive = {
			link: linkMethod,
		};
		return directive;
	}

	function linkMethod(scope, element, attributes){

		var background_image;
		
		var settings = JSON.parse(localStorage.getItem('settings'));

		if (settings != null) {
			if(settings.background_image != null){
				background_image = "url(/uploads/background_images/" + settings.background_image.file +")";
			}
		}

		element.css({
		    'background-image': ((background_image == null) ? "url(/themes/default/photos/background.png)" : background_image)
		});
	}

}());