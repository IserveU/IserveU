(function () {

	'use strict';

	angular
		.module('iserveu')
		.directive('imageManager', imageManager);

	function imageManager() {


		function imageController() {

		}


		return {
			controller: imageController,
			controllerAs: 'image',
			templateUrl: 'app/components/admin/partials/images/images.tpl.html'
		}


	}



})();