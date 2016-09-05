(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('loginService', ['$rootScope', 'auth', 'afterauth', 'ToastMessage',
			loginService]);

  	 /** @ngInject */
	function loginService($rootScope, auth, afterauth, ToastMessage) {

		var factory = {
			creating: false,
			loggingIn: false,
			publicComputer: false,
			authError: false,
			credentials: { email: '', password: '' },
			newUser: { first_name: '',
					   last_name: '',
				       email: '',
				       community_id: '',
					   password: '',
					   agreement_accepted: 1 },
			errors: { emailNotValid: false,
					  invalidCredentials: false,
					  invalidEmail: false,
					  accountLocked: false },
			createUser: createUser,
			login: login,
		};

		function login(credentials) {

			factory.loggingIn = true;

			auth.login( credentials )
				.then(function(r) {

				successHandler(r.data.user, r.data.api_token);
			
			}, function(e) {

				factory.loggingIn = false;
				errorHandler( e.data );
			
			});		
		};

		function createUser() {

			factory.creating = true;
			
			auth.postUserCreate( factory.newUser )
				.then(function(r){
				
				successHandler(r.data.user, r.data.api_token);
			
			}, function(e) {

				factory.creating = false;
				errorHandler( e.data );
				factory.authError = true;

			});
		};

		function successHandler(user, token) {

			$rootScope.authenticatedUser = user;
			
			afterauth.setLoginAuthDetails(user, token);

			localStorage.setItem('public_computer', factory.publicComputer);
		}


		function errorHandler(message) {
			
			for (var i in factory.error) 
				factory.error[i] = false;

			console.log(message);

			if( message.error == "Invalid credentials" ){
				factory.errors.invalidCredentials = true;
			}
			else if(message.error == "Email address not in database"){
				factory.errors.invalidEmail = true;
			}
			else if(angular.isString(message.error) && message.error.substr(0, 17) == 'Account is locked'){
				factory.errors.accountLocked = true;
			}
			else if( angular.isArray(message.error) ){
				for(var i in message.error){
					if(message.error[i] == "validation.unique" )
						factory.errors.emailNotValid = true;
				}
			}
			else {
				ToastMessage.report_error(message);
			}
		};

		return factory;
	}


})();