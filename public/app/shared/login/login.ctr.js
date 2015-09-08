(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('loginController', login);

	function login($rootScope, $state, auth, afterauth, backgroundimage, resetPasswordService, ToastMessage) {	

		var vm = this;

		vm.email;
		vm.password;
		vm.first_name;
		vm.last_name;
		vm.registerform = false;
		vm.emailValidation = false;
		vm.passwordreminder = false;
		vm.invalidCredentials = false;
		vm.passwordreset = false;

		vm.login = login;

		function login(email, password) {

			var credentials = { 
				email: vm.email, 
				password: vm.password
			};

			auth.login(credentials).then(function(data) {
				setLocalStorage(credentials);
			}, function(error) {
				if(error.data.error == "invalid_credentials"){
					vm.invalidCredentials = true;
				}
				else{
					ToastMessage.report_error(error.data);
				}
			});		
		};

		function setLocalStorage(credentials) {
			auth.postAuthenticate(credentials).then(function(data) {
				afterauth.setLoginAuthDetails(data);
				getSettings();
			});
		}   
		
		vm.extendregister = function() {
			vm.registerform = !vm.registerform;
		};

		vm.forgotPassword = function() {
			vm.passwordreminder = !vm.passwordreminder;
		}


		vm.createUser = function(first_name, last_name, email, password){
			var registerinfo = {
				first_name: vm.first_name,
				last_name: vm.last_name,
				email: vm.email,
				password: vm.password
			};
			
			auth.postUserCreate(registerinfo).then(function(result){
				login(registerinfo);
			}, function(error) {
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
			auth.getSettings().then(function(result){
				localStorage.setItem('settings', JSON.stringify(result.data));
			})
		}
	
		$rootScope.$on('refreshLocalStorageSettings', function(event, data) {
			getSettings();
		});

		getSettings();
    }

}());