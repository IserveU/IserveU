(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('setBackImg', ['SETTINGS_JSON', setBackImg]);

	function setBackImg(SETTINGS_JSON) {
		return {
			link: function (scope, element, attrs){

				console.log(SETTINGS_JSON);

				element.css({
				    'background-image': 'url('+ (SETTINGS_JSON.background_image || '/themes/default/photos/background.png')+')'
				});
			}
		};
	}



})();