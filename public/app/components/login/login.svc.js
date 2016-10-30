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
		loginServiceFactory]);

  	 /** @ngInject */
	function loginServiceFactory($rootScope, $timeout, authResource, ToastMessage, utils, redirectService, motionIndex) {

		var Login = {
			creating: false,
			loggingIn: false,
			publicComputer: false,
			authError: false,
			credentials: {
				email: '',
   	    password: ''
		  },
			newUser: {
				first_name: '',
				last_name: '',
			  email: '',
			  date_of_birth: '',
			  community_id: '',
				password: '',
				agreement_accepted: true
      },
			errors: {
				emailNotValid: false,
        invalidCredentials: false,
        invalidEmail: false,
        accountLocked: false,
        default: {}
      },
			clearCredentials: clearCredentials,
			clearErrorMessages: clearErrorMessages,
			createUser: register,
			login: login,
			// made public for resetPassword
			successHandler: successHandler
		};

		function clearCredentials(redirect) {
			$rootScope.authenticatedUser = null;
			$rootScope.userIsLoggedIn = false;
			localStorage.clear();
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

		function register() {

			Login.creating = true;

			if (Login.newUser.date_of_birth.length > 1) {
				Login.newUser.date_of_birth = utils.date.stringify(Login.newUser.date_of_birth);
			}

			authResource.register( Login.newUser ).then(successHandler, function(error) {
				Login.creating  = false;
				Login.authError = true;

				errorHandler( error.data );
			});
		}

		function successHandler(res) {
			var user = res.user || res.data || res;

			$rootScope.userIsLoggedIn    = true;
			$rootScope.authenticatedUser = user;
			$rootScope.authenticatedUser.permissions = utils
				.transformObjectToArray($rootScope.authenticatedUser.permissions);

			localStorage.setItem( 'api_token', user.api_token );
			localStorage.setItem( 'user', JSON.stringify(user) );
			localStorage.setItem( 'public_computer', Login.publicComputer );

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
				case "validation.unique":
					Login.errors.emailNotValid = true;
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