(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('UserbarController', UserbarController);

	function UserbarController($scope, $rootScope, $state, auth, UserbarService, $mdSidenav, SetPermissionsService) {

		var vm = this;
		vm.showUser = false;

		vm.userbarservice = UserbarService;
		vm.setpermissionservice = SetPermissionsService;

		vm.toggleSidebar = function(menuId){
			$mdSidenav(menuId).toggle();
		}

		vm.logout = function() {
			auth.logout().then(function(data) {
				localStorage.removeItem('user');
				localStorage.removeItem('permissions');
				$rootScope.authenticatedUser = null;
				$rootScope.userIsLoggedIn = false;
				$state.go('login', {});			
			});
		}

		vm.showUserSideBar = function() {
			$rootScope.$emit('userListIsClicked');
		}


	}

})();