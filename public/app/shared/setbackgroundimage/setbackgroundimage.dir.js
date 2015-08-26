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
		var backgroundimage;
		var settings = JSON.parse(localStorage.getItem('settings'));

		if (settings.background_image == null || settings) {
			backgroundimage = "url(/themes/default/photos/background.png)";
		}
		else if (settings.background_image != null){
			backgroundimage = "url(/uploads/background_images/" + settings.background_image.file +")";
		}
		element.css({
		   'background-image': backgroundimage
		});
	}

}());