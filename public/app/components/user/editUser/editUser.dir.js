(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('editUser',
			['$stateParams',
			 'communityResource',
			 'editUserFormService',
			 'editUserFactory',
			 'userResource',
			 'userToolbarService',
			 'roleFactory',
			 'utils',
		editUser]);

	/** @ngInject */
	function editUser($stateParams, communityResource, editUserFormService, editUserFactory, userResource, userToolbarService, roleFactory, utils) {

		function editUserController($scope) {

			function fetchCommunities() {
				communityResource.getCommunities().then(function(results) {
					$scope.communities = results.data;
				});
			}

			function saveField(ev, item) {
				item.saving = true;

				var data = editUserFormService.delegateProfileData(item.label.toLowerCase(), $scope.profile);
				var id   = $scope.profile.id;

				userResource.updateUser(id, data).then(function(results) {
					setUserProfile();
					toggleItem(item);
				}, function(error) {
					toggleItem(item);
				});
			}

			function toggleItem(item) {
				item.saving = false;
				item.edit   = !item.edit;
			}

			function setUserProfile() {
				editUserFormService.setUserProfileFields($scope.profile);
			}

			(function init() {
				fetchCommunities();
				setUserProfile();
				// not sure what this is doing
				// userToolbarService.state = '';
			})();

			(function exposeScopeMethods() {
				$scope.communities = {};
				$scope.edit        = editUserFactory;
				$scope.roles       = roleFactory;
				$scope.form        = editUserFormService;
				$scope.saveField   = saveField;
			})();
		}

		return {
			controller: ['$scope', editUserController],
			controllerAs: 'editUser',
			templateUrl: 'app/components/user/editUser/editUser.tpl.html'
		}

	}


})();