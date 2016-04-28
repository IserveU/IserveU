(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('editUser', ['editUserFactory', 'userToolbarService', 'roleFactory', editUser]);

	/** @ngInject */
	function editUser(editUserFactory, userToolbarService, roleFactory) {

		function editUserController($scope) {

			userToolbarService.state = '';
			$scope.edit = editUserFactory;
			$scope.roles = roleFactory;

		}

		return {
			controller: ['$scope', editUserController],
			templateUrl: 'app/components/user/components/edit-user/edit-user.tpl.html'
		}

	}


})();