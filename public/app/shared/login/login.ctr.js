(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('loginController', login);

	function login($scope, $http, $rootScope, $state, $location, auth, $mdDialog, $window, backgroundimage) {	

		var vm = this;

		vm.email;
		vm.password;
		vm.first_name;
		vm.last_name;
		vm.loginError = false;
		vm.registerform = false;
		vm.emailValidation = false;
		vm.passwordreminder = false;
		vm.login = login;
		vm.background_image;
		vm.default_background = true;
		vm.background_url = '/themes/default/photos/background.png';
		vm.redirectUrlName;
		vm.redirectUrlID;

		// variables from reset password service
		// vm.passwordreset = resetpassword.resetpassword_box;
		// vm.resetpassword = function(){
		// 	var data =
		// 	resetpassword.reset(data);
		// }

		// console.log(vm.passwordreset);


		function login(email, password) {

			var credentials = { 
				email: vm.email, 
				password: vm.password
			};

			auth.login(credentials).then(function(data) {
				if(data.status == 401){
					vm.loginError = true;
				}
				vm.redirectUrlName = $rootScope.redirectUrlName;
				vm.redirectUrlID = $rootScope.redirectUrlID;
				setLocalStorage(credentials);
			}, function(error) {
				vm.error = error;
				vm.loginError = true;
			});		
		};

		function setLocalStorage(credentials) {
			auth.postAuthenticate(credentials).then(function(data) {
				localStorage.setItem('user', JSON.stringify(data.data.user));
				localStorage.setItem('permissions', JSON.stringify(data.data.user.permissions));
				$rootScope.userIsLoggedIn = true;
				$rootScope.authenticatedUser = data.data.user;
				if(vm.redirectUrlName){
					$state.go(vm.redirectUrlName, {"id": vm.redirectUrlID});
				}
				else{
					$state.go('home');
				}
			});
		}   
		
		vm.extendregister = function() {
			vm.registerform = !vm.registerform;
		};

		vm.forgotPassword = function() {
			vm.passwordreminder = !vm.passwordreminder;
		}

		vm.passwordreset = function(email) {
            $state.reload();
			//send email function
		}

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

		vm.crossControllerTest = function() {
			console.log(test);
		}

		function getSettings(){
			auth.getSettings().then(function(result){
				localStorage.setItem('settings', JSON.stringify(result.data));
			})
		}
	
		getSettings();
    }

}());