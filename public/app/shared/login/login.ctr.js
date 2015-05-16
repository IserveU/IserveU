(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('loginController', login);

	function login($scope, $http, $rootScope, $location, auth, $mdDialog) {

		

		var vm = this;

		vm.loginError = false;

		vm.logUserIn = function(email, password) {

			var credentials = { email: email, password: password };

			auth.login(credentials).success(function(data) {
				vm.loginError = false;
				$rootScope.userIsLoggedIn = true;
				$location.url('/app/home')
			}).error(function(data) {
				console.log('There was an error logging in');
			});		
		}

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

		//vm.initalizeForm = function(){
	        $mdDialog.show({
	        	templateUrl: '/app/shared/login/form.tpl.html'
	        });
    //	}
        
       
		
	}

})();