(function() {

	'use strict';

	angular
		.module('app.login')
		.component('loginComponent', {
      controller: LoginController,
      controllerAs: 'login',
      templateUrl: 'app/components/login/login.tpl.html'
    });

  LoginController.$inject = ['AuthResource', 'Login', 'ResetPassword', 'ToastMessage'];
	function LoginController(AuthResource, Login, ResetPassword, ToastMessage) {

    /*****************************************************************
    *
    *	Public Functions
    *
    ******************************************************************/

    /** Global context for this */
    var self = this;

		self.extendRegisterForm = extendRegisterForm;
		self.forgotPassword = showForgotPassword;
		self.passwordreminder = false;
		self.registerform = false;
		self.sendResetPassword = sendResetPassword;
		self.service = Login;

		function extendRegisterForm() {
			self.registerform = !self.registerform;
		}

		function showForgotPassword() {
			self.passwordreminder = !self.passwordreminder;
		}

		function sendResetPassword(){
			AuthResource.resetPassword(Login.credentials).then(function(results) {
        ToastMessage.simple(results.data.message);
	    },
      function(error) {
        ToastMessage.simple("Email address not found.");
      });
		}

    /*****************************************************************
    *
    *	Initialization
    *
    ******************************************************************/

    self.$onInit = function() {
      Login.clearErrorMessages();
      Login.loggingIn = false;
      ResetPassword.checkToken();
    };

	}
})();
