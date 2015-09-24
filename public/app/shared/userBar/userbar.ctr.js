(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('UserbarController', UserbarController);

	function UserbarController($rootScope, $translate, auth, UserbarService, SetPermissionsService) {

		var vm = this;

		vm.userbarservice = UserbarService;
		vm.setpermissionservice = SetPermissionsService;

		vm.preferredLang = "English";

		vm.languages = [{name:'English', key:'en'},
			{name:'French', key:'fr'}];

		vm.changeLanguage = function(langKey){
			$translate.use(langKey);
		}

		vm.logout = function() {
			auth.logout().then(function(data) {
				localStorage.clear();
				$rootScope.authenticatedUser = null;
				$rootScope.userIsLoggedIn = false;
				$state.go('login', {});			
			});
		}

	}

})(); 