(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('UserbarController', UserbarController);

	function UserbarController($scope, $rootScope, $state, auth, UserbarService) {

		var vm = this;
		vm.showUser = false;
		$rootScope.createMotion = false;
		$rootScope.canDeleteMotion = false;

		vm.userbarservice = UserbarService;

	

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

		var permissions = JSON.parse(localStorage.getItem('permissions'));
		if(permissions == null){
			$state.go('home');
		}
		if(permissions.indexOf("show-users") != -1) {
			vm.showUser = true;
		}
		if(permissions.indexOf("create-motions") != -1) {
			$rootScope.createMotion = true;
		}
		if(permissions.indexOf("delete-motions") != -1) {
			$rootScope.canDeleteMotion = true;
		}
		// if(permissions.indexOf("edit-users") != -1) {
			
		// }
	}

})();