(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('loginModalController', loginModalController);

	function loginModalController($scope, auth, $rootScope, $state) {

		var vm = this;

		vm.showLoginError = false;

		vm.submit = function(email, password) {

			var credentials = { email:email, password:password };

			auth.login(credentials).success(function(data, status) {
				auth.isLoggedIn().success(function(user) {
					if(user != "not logged in") {
						$rootScope.userIsLoggedIn = true;
						$rootScope.currentUser = user;
						$scope.$close(user);
					}
					else {
						$rootScope.userIsLoggedIn = false;
						$rootScope.currentUser = undefined;
						vm.showLoginError = true;
					}
				});		
			});
		}

		vm.cancel = function() {
			$scope.$dismiss();
			$state.go('home');
		}
	}
})();