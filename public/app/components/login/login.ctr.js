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

		/** exports functions to local scope (binded to 'this') */
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


		/** Initializes data, and also resets on state change. */

		loginService.loggingIn = false;
		resetPasswordService.check();
    }

}());