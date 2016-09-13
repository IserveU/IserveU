(function() {

	'use strict';
	
	angular
		.module('iserveu')
		.directive('loginPortal', [
			'authResource', 
			'loginService', 
			'resetPasswordService', 
			'ToastMessage',
		loginPortal]);

	function loginPortal(authResource, loginService, resetPasswordService, ToastMessage) {

		function loginPortalController($scope) {

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
				loginService.clearErrorMessages();
				loginService.loggingIn = false;
				resetPasswordService.check();
			})();

		}



		return {
			controller: ['$scope', loginPortalController],
			controllerAs: 'login',
			templateUrl: 'app/components/login/login.tpl.html'
		}


	}

})();