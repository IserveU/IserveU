(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('loginController', login);

	function login($scope, $http, $rootScope, $state, $location, auth, $mdDialog, $window) {	

		var vm = this;

		vm.email;
		vm.password;
		vm.first_name;
		vm.last_name;
		vm.loginError = false;
		vm.registerform = false;
		vm.emailValidation = false;
		vm.login = login;


		function login(email, password) {

			var credentials = { 
				email: vm.email, 
				password: vm.password
			};

			auth.login(credentials).then(function(data) {
				if(data.status == 401){
					vm.loginError = true;
				}
				else {
					auth.postAuthenticate(credentials).then(function(data) {
						localStorage.setItem('user', JSON.stringify(data.data.user));
						localStorage.setItem('permissions', JSON.stringify(data.data.user.permissions));
						$rootScope.userIsLoggedIn = true;
						$rootScope.authenticatedUser = data.data.user;
					});
				}

			}, function(error) {
				vm.error = error;
				vm.loginError = true;
			});		
		};   
		
		vm.extendregister = function() {
			vm.registerform = !vm.registerform;
		};

		vm.createUser = function(first_name, last_name, email, password){
			var registerinfo = {
				first_name: vm.first_name,
				last_name: vm.last_name,
				email: vm.email,
				password: vm.password
			};
			
			auth.postUserCreate(registerinfo).then(function(result){
				if(result.status === 200) {
					login(registerinfo);
				}	
			}, function(error) {
				if(error.status === 400) {
					// waiting on api to send back more verbose error messages
					console.log(error);
					vm.emailValidation = true;
				}
				
			});
		};

	

    }

}());