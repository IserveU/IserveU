(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('editUser',
			['communityResource',
			'editUserFormService',
			'userResource',
			'userPreferenceResource',
			'userPreferenceFactory',
			'userRoleResource',
			'userRoleFactory',
			'Authorizer',
			'ToastMessage',
			'utils',
		editUser]);

	/** @ngInject */
	function editUser(communityResource, editUserFormService, userResource, userPreferenceResource, userPreferenceFactory, userRoleResource, userRoleFactory, Authorizer, ToastMessage,utils) {

		function editUserController($scope) {

			function destroy() {	
				ToastMessage.destroyThis("user", function(){
				// we will change to slug later, so this need to be changed as well. 
				userResource.deleteUser($scope.profile.id);
				});
	        }
			function fetchCommunities() {
				communityResource.getCommunities().then(function(results) {
					$scope.communities = results.data;
				});
			}
			function fetchUserRoles() {

				if (!Authorizer.canAccess('administrate-permission')) {
					return false;
				}

				var slug = $scope.profile.slug;
				$scope.profile.roles = [];

				userRoleResource.getUserRole(slug).then(transformRoles, function(error) {
					throw new Error('Unable to get this user\'s roles.');
				});

				function transformRoles(results) {
					$scope.roles = results.data || results;
					for (var i in $scope.roles)
						if ($scope.roles[i].id)
							$scope.profile.roles.push($scope.roles[i].name);
				}
			}

      function fetchUserPreferences() {
        var slug = $scope.profile.slug;
        $scope.profile.preferences = [];
        $scope.preferencesFactory = userPreferenceFactory;
        userPreferenceResource.getUserPreferences(slug).then(function(results) {
          $scope.preferencesFactory.initPreferences(results);
        });
      }

			function saveField(ev, item) {
				item.saving = true;

				var data = editUserFormService.delegateProfileData(item.label.toLowerCase(), $scope.profile);
				var id   = $scope.profile.id;
				var slug = $scope.profile.slug;

				userResource.updateUser(slug, data).then(function(results) {
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

      // Checks if the user has the requested permission
      function hasPermission(perm) {
        return $scope.profile.permissions.indexOf(perm) > -1 ? true : false;
      }

			(function init() {
				fetchCommunities();
				fetchUserRoles();
        		fetchUserPreferences();
				setUserProfile();
			})();
		
			(function exposeScopeMethods() {
				$scope.communities = {};
				$scope.roles       = {};
				$scope.form        = editUserFormService;
				$scope.roleFactory = userRoleFactory;
				$scope.saveField   = saveField;
				$scope.fetchUserRoles = fetchUserRoles;
				$scope.destroy 	   = destroy;
				$scope.hasPermission      = hasPermission;
							})();
		}

		return {
			controller: ['$scope', editUserController],
			controllerAs: 'editUser',
			templateUrl: 'app/components/user/editUser/editUser.tpl.html'
		}

	}


})();
