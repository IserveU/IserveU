(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('editUser', ['communityIndex','editUserFactory', 'userToolbarService', 'roleFactory', editUser]);

	/** @ngInject */
	function editUser(communityIndex, editUserFactory, userToolbarService, roleFactory) {

		function editUserController($scope) {

			userToolbarService.state = '';
			$scope.edit = editUserFactory;
			$scope.roles = roleFactory;
			$scope.communities = communityIndex;

			communityIndex.loadAll();
		}

		return {
			controller: ['$scope', editUserController],
			templateUrl: 'app/components/user/components/edit-user/edit-user.tpl.html'
		}

	}


})();