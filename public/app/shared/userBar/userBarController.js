(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('userBarController', userBarController)
		// .config(LoginConfig);

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
					}
					else {
						$rootScope.userIsLoggedIn = false;
						$rootScope.currentUser = undefined;
						vm.showLoginError = true;
					}
				});		
			});
		}

		console.log('hey');

	}

	// function LoginConfig($stateProvider) {
 //        $stateProvider
 //            .state( 'app.login', {
 //                url: '/login',
 //                templateUrl: 'app/shared/login/loginView.html',
 //                data: {
 //                    requireLogin: false
 //                }
 //        	});
 //    };
})();