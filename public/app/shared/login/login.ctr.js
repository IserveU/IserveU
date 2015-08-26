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
		vm.background_image;
		vm.default_background = true;
		vm.background_url = '/themes/default/photos/background.png';
		vm.redirectUrlName;
		vm.redirectUrlID;

		function login(email, password) {

			var credentials = { 
				email: vm.email, 
				password: vm.password
			};

			auth.login(credentials).then(function(data) {
				if(data.status == 401){
					vm.loginError = true;
				}
				setLocalStorage(credentials);
			}, function(error) {
				vm.error = error;
				vm.loginError = true;
			});		
		};

		function setLocalStorage(credentials) {
			auth.postAuthenticate(credentials).then(function(data) {
				afterauth.setLoginAuthDetails(data);
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