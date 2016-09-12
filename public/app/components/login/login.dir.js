// (function() {

// 	'use strict';
	
// 	angular
// 		.module('iserveu')
// 		.directive('loginPortal', [
// 			'authResource', 
// 			'loginService', 
// 			'resetPasswordService', 
// 			'ToastMessage',
// 		loginPortal]);

// 	function loginPortal(authResource, loginService, resetPasswordService, ToastMessage) {

// 		function loginPortalController($scope) {

// 			(function init() {
// 				loginService.loggingIn = false;
// 				resetPasswordService.check();
// 			})();

// 			var self = this;

// 			self.extendRegisterForm = extendRegisterForm,
// 			self.forgotPassword = showForgotPassword,
// 			self.passwordreminder = false,
// 			self.registerform = false,
// 			self.sendResetPassword = sendResetPassword,
// 			self.service = loginService


// 			function extendRegisterForm () {
// 				console.log('extendRegisterForm2');
// 				self.registerform = !self.registerform;
// 			}

// 			function showForgotPassword() {
// 				self.passwordreminder = !self.passwordreminder;
// 			}

// 			function sendResetPassword(){
// 				authResource.resetPassword( loginService.credentials ).then(function(results) {
// 					ToastMessage.simple('Your email has been sent!');
// 				});
// 			}
		


// 		}



// 		return {
// 			controller: ['$scope', loginPortalController],
// 			controllerAs: '$ctrl',
// 			templateUrl: 'app/components/login/login.tpl.html'
// 		}


// 	}

// })();