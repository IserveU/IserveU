(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('loginController', [
			'loginService', 
			'auth', 
			'resetPasswordService', 
			'ToastMessage',
			'utils',
		login]);

	function login(loginService, auth, resetPasswordService, ToastMessage, utils) {	

		this.service = loginService;
		this.extendRegisterForm = extendRegisterForm;
		this.forgotPassword = forgotPassword;
		this.sendResetPassword = sendResetPassword;

		function extendRegisterForm() {
			this.registerform = !this.registerform;
		};

		function forgotPassword() {
			this.passwordreminder = !this.passwordreminder;
		};

		function sendResetPassword(){
			auth.getResetPassword( loginService.credentials ).then(function(r) {

				ToastMessage.simple('Your email has been sent!');

			}, function(e) { console.log(e); });
		};


		// Resets data
		loginService.loggingIn = false;
		resetPasswordService.check();
    }

}());