(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('backgroundImageManager', ['settings', 
			backgroundImageManager]);

	/** @ngInject */
	function backgroundImageManager(settings) {

		return {
			replace: true,
			controller: function () {
				this.settings = settings;
				this.today    = settings.data.background_image || '/themes/default/photos/background.png';
				this.save = function() {
					settings.saveArray('background_image', '/uploads/'+JSON.parse(this.uploaded).filename);
				}
			},
			controllerAs: 'bkg',
			templateUrl: 'app/components/admin.dash/background-image/backgroundimage-manager.tpl.html'
		}
	}


})();