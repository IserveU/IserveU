(function(){
	'use strict';

	angular
		.module('iserveu')
		.directive('appearanceManager', appearance);

	/** @ngInject */
	function appearance(settings, palette, ToastMessage) {

		function appearanceController($scope) {

			this.settings = settings.getData();
			this.service  = settings;
			this.palette  = palette;

			$scope.$watch(
				'appearance.settings.saving',
				function redirect(newValue, oldValue){
					if(newValue == false && oldValue == true)
						ToastMessage.reload(800);
			});
		}


		return {
			controller: appearanceController,
			controllerAs: 'appearance',
			templateUrl: 'app/components/admin/partials/appearance/appearance.tpl.html'
		}



	}



})();