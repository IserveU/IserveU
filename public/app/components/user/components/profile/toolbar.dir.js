(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('profileToolbar', profileToolbar);

	/** @ngInject */
	function profileToolbar($state, user, editUserFactory, userToolbarService) {


		function profileToolbarController($scope) {

			$scope.toolbar = userToolbarService;

			$scope.$watch("toolbar.edit.success['last_name']",
				function(newValue, oldValue){
					if(newValue === false && oldValue === true)
						userToolbarService.showInputField = false;
			}, true);	
		}

		return {
			controller: profileToolbarController,
			templateUrl: 'app/components/user/components/profile/toolbar.tpl.html'
		}

	}

})();