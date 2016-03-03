(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('editUser', editUser);

	/** @ngInject */
	function editUser(editUserFactory, userToolbarService, roleFactory) {

		function editUserController($scope) {

			userToolbarService.state = '';
			$scope.edit = editUserFactory;
			$scope.roles = roleFactory;

		}

		return {
			controller: editUserController,
			templateUrl: 'app/components/user/components/edit-user/edit-user.tpl.html'
		}

	}


})();