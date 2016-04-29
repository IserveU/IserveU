(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('editUser', ['COMMUNITY_INDEX','editUserFactory', 'userToolbarService', 'roleFactory', editUser]);

	/** @ngInject */
	function editUser(COMMUNITY_INDEX, editUserFactory, userToolbarService, roleFactory) {

		function editUserController($scope) {

			userToolbarService.state = '';
			$scope.edit = editUserFactory;
			$scope.roles = roleFactory;
			$scope.communities = COMMUNITY_INDEX;

		}

		return {
			controller: ['$scope', editUserController],
			templateUrl: 'app/components/user/components/edit-user/edit-user.tpl.html'
		}

	}


})();