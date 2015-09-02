(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('loginController', login);

	function login($rootScope, $state, auth, afterauth, backgroundimage, resetPasswordService) {	

		var vm = this;

		vm.email;
		vm.password;
		vm.first_name;
		vm.last_name;
		vm.loginError = false;
		vm.registerform = false;
		vm.emailValidation = false;
		vm.passwordreminder = false;
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
				vm.loginError = true;
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
				if(error.status === 400) {		// need more verbose error messages to inform user
					vm.emailValidation = true;
				}
				
			});
		};

		function getSettings(){
			auth.getSettings().then(function(result){
				localStorage.setItem('settings', JSON.stringify(result.data));
			})
		}
	
		$rootScope.$on('backgroundImageUploaded', function(event, data) {
			getSettings();
		});

		getSettings();
    }

}());