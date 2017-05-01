(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('loginService', [
			'$rootScope',
			'$timeout',
			'authResource',
			'ToastMessage',
			'utils',
			'redirectService',
			'motionIndex',
      'localStorageManager',
		loginServiceFactory]);

  	 /** @ngInject */
	function loginServiceFactory($rootScope, $timeout, authResource, ToastMessage, utils, redirectService, motionIndex, localStorageManager) {
		var Login = {
			creating: false,
			loggingIn: false,
			rememberMe: true,
			authError: false,
			credentials: {
				email: '',
   	    password: ''
		  },
			errors: {
				emailNotValid: false,
        invalidCredentials: false,
        accountLocked: false,
        default: {}
      },
			clearCredentials: clearCredentials,
			clearErrorMessages: clearErrorMessages,
			login: login,
			// made public for resetPassword
			successHandler: successHandler
		};
    
    /**
     * Clears out the logged in user and logs out
     */
		function clearCredentials(redirect) {
			$rootScope.authenticatedUser = null;
			$rootScope.userIsLoggedIn = false;
      localStorageManager.logout();
			motionIndex.clear();
			if (redirect) { 
        redirectService.onLogout();
			}
		}
 
		function clearErrorMessages() {
			Login.authError = false;
			for (var i in Login.errors) {
				if (Login.errors[i])
					Login.errors[i] = false;
			}
		}

		function login(credentials) {
			Login.loggingIn = true;

			authResource.login(credentials).then(successHandler, function(error) {
				Login.loggingIn = false;
				errorHandler( error.data );
			});
		}


		function successHandler(res) {
      localStorageManager.remove('agreement_accepted');
      
			var user = res.user || res.data || res;

			$rootScope.userIsLoggedIn    = true;
			$rootScope.authenticatedUser = user;
			$rootScope.authenticatedUser.permissions = utils
				.transformObjectToArray($rootScope.authenticatedUser.permissions);

      motionIndex.clear();

			localStorageManager.login(user, Login.rememberMe );

      // Temporary fix to the user agreement not having a service and directive
      if(!user.agreement_accepted){
          window.location.href = "/";
      }


			$timeout(function() { redirectService.redirect() }, 250 );
		}

		function errorHandler(responseError) {

			clearErrorMessages();

			var error = responseError.error ? responseError.error.toLowerCase() : responseError;

			switch(error) {
				case "invalid credentials":
					Login.errors.invalidCredentials = true;
					break;
				case "email address not in database":
					Login.errors.invalidEmail = true;
					break;
				case "account is locked":
					Login.errors.accountLocked = true;
					break;
				default:
					Login.errors.default = {
						show: true,
						message: responseError.message || "Something went wrong!"
					}
					console.error(responseError);
					break;
			}
		}

		return Login;
	}


})();