(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('backgroundImageManager', backgroundImageManager);

	/** @ngInject */
	function backgroundImageManager(settings, fileService, ToastMessage) {

		function backgroundimageController() {

			this.settings = settings.getData();

			this.today = this.settings.theme.background_image 
						? this.settings.theme.background_image :
						'/themes/default/photos/background.png';

			this.save = function(file) {
				settings.saveArray('background_image', '/uploads/'+JSON.parse(this.uploaded).filename);
			}
		}



		return {
			controller: backgroundimageController,
			controllerAs: 'bkg',
			templateUrl: 'app/components/backgroundimage/components/backgroundimage-manager/backgroundimage-manager.tpl.html'
		}
	}


})();