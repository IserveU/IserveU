(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('UserbarController', UserbarController);

	function UserbarController($scope, $rootScope, $state, auth, UserbarService, $mdSidenav) {

		var vm = this;
		vm.showUser = false;
		$rootScope.createMotion = false;
		$rootScope.canDeleteMotion = false;
		$rootScope.showUsers = false;
		$rootScope.canCreateBackgroundImages;

		vm.userbarservice = UserbarService;

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

		var permissions = JSON.parse(localStorage.getItem('permissions'));
		if(permissions == null){
			console.log('there are no permissions');
			$state.go('home');
		}
		if(permissions.indexOf("show-users") != -1) {
			$rootScope.showUsers = true;
		}
		if(permissions.indexOf("create-motions") != -1) {
			$rootScope.createMotion = true;
		}
		if(permissions.indexOf("administrate-motions") != -1) {
			$rootScope.administrateMotion = true;
		}
		if(permissions.indexOf("administrate-users") != -1) {
			$rootScope.administrateUsers = true;
		}
		if(permissions.indexOf("delete-motions") != -1) {
			$rootScope.canDeleteMotion = true;
		}
		if(permissions.indexOf("create-background_images") != -1) {
			$rootScope.canCreateBackgroundImages = true;
		}
		if(permissions.indexOf("create-properties") != -1) {
			$rootScope.createProperties = true;
		}
		if(permissions.indexOf("administrate-properties") != -1) {
			$rootScope.administrateProperties = true;
		}
	}

})();