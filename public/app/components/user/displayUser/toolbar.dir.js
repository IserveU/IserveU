(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('profileToolbar', ['userToolbarService', profileToolbar]);

	/** @ngInject */
	function profileToolbar(userToolbarService) {


		function profileToolbarController($scope) {

			$scope.toolbar = userToolbarService;

			$scope.$watch("toolbar.edit.success['last_name']",
				function(newValue, oldValue){
					if(newValue === false && oldValue === true)
						userToolbarService.showInputField = false;
			}, true);	
		
		}

		return {
			controller: ['$scope', profileToolbarController],
			templateUrl: 'app/components/user/displayUser/toolbar.tpl.html'
		}

	}

})();