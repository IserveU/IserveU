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
			creating:       false,
			loggingIn:      false,
			publicComputer: false,
			authError:      false,
			credentials:  { email: '', 
					   	    password: '' 
						  },
			newUser:      { first_name: '',
					        last_name: '',
				            email: '',
				            community_id: '',
					        password: '',
					        agreement_accepted: true
					      },
			errors:       { emailNotValid: false,
					        invalidCredentials: false,
					        invalidEmail: false,
					        accountLocked: false,
					        default: {}
					      },
			clearCredentials: clearCredentials,
			createUser:   register,
			login:        login
		};

		function clearCredentials(redirect) {
			$rootScope.authenticatedUser = null;
			$rootScope.userIsLoggedIn = false;
			localStorage.clear();
			motionIndex.clear();

			if(redirect) {
				redirectService.onLogout();
			}
		}

		function login(credentials) {

			Login.loggingIn = true;

			authResource.login( credentials ).then(function(results) {
				successHandler( results.data );
			}, function(error) {
				Login.loggingIn = false;
				errorHandler( error.data );
			});		
		};

		function register() {

			Login.creating = true;
			
			authResource.register( Login.newUser ).then(function(results){
				successHandler( results.data );			
			}, function(error) {
				Login.creating  = false;
				Login.authError = true;

				errorHandler( error.data );
			});
		};

		function successHandler(user) {

			$rootScope.userIsLoggedIn    = true;
			$rootScope.authenticatedUser = user;

			localStorage.setItem( 'api_token', user.api_token );
			localStorage.setItem( 'user', JSON.stringify(user) );
			localStorage.setItem( 'public_computer', Login.publicComputer );

			$timeout(function() { redirectService.redirect() }, 250 );
		}

		function errorHandler(responseError) {
			for (var i in Login.errors) // resets all erorrs
				Login.errors[i] = false;

			var error = responseError.error.toLowerCase();

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
						message: responseError.message
					}
					break;
			}
		};

		return Login;
	}


})();