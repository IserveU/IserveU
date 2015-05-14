(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('userBarController', userBarController);

	function userBarController($scope, auth, $rootScope, $state, $timeout, $mdSidenav, $log) {

		var vm = this;

		vm.close = function () {
			$mdSidenav('user-bar').close()
		    	.then(function () {
					$log.debug("close user-bar is done");
		    });
		}

		vm.loginError = false;

		vm.logUserOut = function() {

			auth.logout().success(function() {
				$rootScope.userIsLoggedIn = false;
				$state.go('app.home');
				$rootScope.currentUser = undefined;

			});			
		}

		vm.submit = function(email, password) {

			var credentials = { email:email, password:password };

			auth.login(credentials).success(function(data, status) {
				auth.isLoggedIn().success(function(user) {
					if(user != "not logged in") {
						$rootScope.userIsLoggedIn = true;
						$rootScope.currentUser = user;
						vm.close(user);
						console.log('user is logged in! ' + data)
					}
					else {
						$rootScope.userIsLoggedIn = false;
						$rootScope.currentUser = undefined;
						vm.showLoginError = true;
					}
				});		
			});
		}

	}
})();