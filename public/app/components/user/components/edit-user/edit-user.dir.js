(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('editUser', editUser);

	/** @ngInject */
	function editUser(editUserFactory, userToolbarService) {

		function editUserController($scope) {

			userToolbarService.state = '';
			$scope.edit = editUserFactory;
		
		}

		return {
			controller: editUserController,
			templateUrl: 'app/components/user/components/edit-user/edit-user.tpl.html'
		}

	}


})();