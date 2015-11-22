(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('loginController', login);

	function login($rootScope, $state, $stateParams, $scope, auth, afterauth, backgroundimage, resetPasswordService, ToastMessage, $mdDialog) {	

		var vm = this;

		vm.email;
		vm.password;
		vm.first_name;
		vm.last_name;
		vm.registerform = false;
		vm.emailValidation = false;
		vm.passwordreminder = false;
		vm.invalidCredentials = false;
		vm.invalidEmail = false;
		vm.passwordreset = false;
		vm.publicComputer = false;

		vm.loggingIn = false;
		vm.creatingUser = false;

		vm.login = login;

		function login(email, password) {
			vm.loggingIn = true;

			var credentials = { 
				email: vm.email, 
				password: vm.password
			};

			auth.login(credentials).then(function(data) {
				setLocalStorage(credentials, vm.publicComputer);
			}, function(error) {
				vm.loggingIn = false;

				console.log(error.data);
				var message = error.data.message;
				if(message == "Invalid credentials"){
					vm.invalidCredentials = true;
				}
				else if(message == "Email address not in database"){
					vm.invalidEmail = true;
				}
				else{
					ToastMessage.report_error(error.data);
				}
			});		
		};

		function setLocalStorage(credentials, publicComputer) {
			auth.postAuthenticate(credentials).then(function(data) {
				afterauth.setLoginAuthDetails(data);
				localStorage.setItem('public_computer', publicComputer);
				getSettings();
			});
		}

		vm.extendregister = function() {
			vm.registerform = !vm.registerform;
		};

		vm.forgotPassword = function() {
			vm.passwordreminder = !vm.passwordreminder;
		}

		vm.sendResetPassword = function(){
			var credentials = {
				email: vm.email,
				password: vm.password
			}
			auth.getResetPassword(credentials).then(function(result) {
				$mdDialog.show(
					$mdDialog.alert()
					.clickOutsideToClose(true)
					.content('Your email has been sent!')
					.ok('Thanks!')
				);
			}, function(error) {
				console.log(error);
			});
		}

		vm.createUser = function(first_name, last_name, email, password){
			vm.creatingUser = true;
			var registerinfo = {
				first_name: vm.first_name,
				last_name: vm.last_name,
				email: vm.email,
				password: vm.password
			};
			
			auth.postUserCreate(registerinfo).then(function(result){
				login(registerinfo);
			}, function(error) {
				vm.creatingUser = false;
				var message = JSON.parse(error.data.message);
				if(message.hasOwnProperty('email')){
					if(message.email[0] == "validation.unique"){
						vm.emailValidation = true;
					}
				}
				else{
					ToastMessage.report_error(message);
				}
			});
		};

		function getSettings(){
			if(!localStorage.getItem('settings')){
				auth.getSettings().then(function(result){
					localStorage.setItem('settings', JSON.stringify(result.data));
				})
			}
		}
	
		resetPasswordService.check();
			getSettings();
    }

}());