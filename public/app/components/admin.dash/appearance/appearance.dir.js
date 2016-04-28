(function(){
	'use strict';

	angular
		.module('iserveu')
		.directive('appearanceManager', ['settings', 'Palette', 'ToastMessage', appearance]);

	/** @ngInject */
	function appearance(settings, Palette, ToastMessage) {

		function appearanceController($scope) {

			this.service  = settings;
			this.palette  = new Palette(settings.data.theme);

			$scope.$watch(
				'appearance.service.data.saving',
				function redirect(newValue, oldValue){
					if(newValue == false && oldValue == true)
						ToastMessage.reload(800);
			});
		}


		return {
			controller: ['$scope', appearanceController],
			controllerAs: 'appearance',
			templateUrl: 'app/components/admin.dash/appearance/appearance.tpl.html'
		}



	}



})();