(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('loginController', login);

  	 /** @ngInject */
	function login(settings, loginService, auth, resetPasswordService, ToastMessage) {	

		this.service = loginService;
		this.settings = settings.getData();

		this.extendRegisterForm = extendRegisterForm;
		this.forgotPassword = forgotPassword;
		this.sendResetPassword = sendResetPassword;
		this.confirm_email = '';

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

		resetPasswordService.check();
    }

}());