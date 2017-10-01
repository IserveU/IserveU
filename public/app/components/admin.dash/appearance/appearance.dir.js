(function(){
	'use strict';

	angular
		.module('app.admin.dash')
		.directive('appearanceManager', ['Settings', 'Palette', 'ToastMessage', appearance]);

	/** @ngInject */
	function appearance(Settings, Palette, ToastMessage) {

		function appearanceController($scope) {

			this.service  = Settings;
			this.palette  = new Palette(Settings.data.theme.colors);

			$scope.$watch(
				'appearance.service.data.saving',
				function redirect(newValue, oldValue) {
					if(!newValue && oldValue)
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