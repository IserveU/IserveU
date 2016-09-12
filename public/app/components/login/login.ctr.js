(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('loginController', [
			'$scope',
			'authResource', 
			'loginService', 
			'resetPasswordService', 
			'ToastMessage',
		login]);

	function login($scope, authResource, loginService, resetPasswordService, ToastMessage) {	

	    /*****************************************************************
	    *
	    *	Public Functions
	    *
	    ******************************************************************/

	    /** Global context for this */
	    var self = this;

		self.extendRegisterForm = extendRegisterForm,
		self.forgotPassword = showForgotPassword,
		self.passwordreminder = false,
		self.registerform = false,
		self.sendResetPassword = sendResetPassword,
		self.service = loginService

		function extendRegisterForm() {
			self.registerform = !self.registerform;
		}

		function showForgotPassword() {
			self.passwordreminder = !self.passwordreminder;
		}

		function sendResetPassword(){
			authResource.resetPassword( loginService.credentials ).then(function(results) {
				ToastMessage.simple('Your email has been sent!');
			});
		}
    
	    /*****************************************************************
	    *
	    *	Initialization
	    *
	    ******************************************************************/

		(function init() {
			// loginService.clearCredentials();
			loginService.loggingIn = false;
			resetPasswordService.check();
		})();
    }

}());