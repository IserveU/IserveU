(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('loginController', login);

  	 /** @ngInject */
	function login(loginService, auth, resetPasswordService, ToastMessage, communityIndex, motionObj, utils, SetPermissionsService) {	

		this.service = loginService;
		this.extendRegisterForm = extendRegisterForm;
		this.forgotPassword = forgotPassword;
		this.sendResetPassword = sendResetPassword;
		this.communities = communityIndex.data;

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
		SetPermissionsService.set(null);
		utils.clearArray(motionObj.data);
		motionObj.next_page = 1;
		loginService.loggingIn = false;
		resetPasswordService.check();
    }

}());