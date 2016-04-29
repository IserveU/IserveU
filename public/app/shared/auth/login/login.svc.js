(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('loginService', ['$rootScope', 'auth', 'afterauth', 'ToastMessage', 'motionObj',
			loginService]);

  	 /** @ngInject */
	function loginService($rootScope, auth, afterauth, ToastMessage, motionObj) {

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
					   password: '' },
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

				successHandler(r.data.user, r.data.token);
			
			}, function(e) {

				factory.loggingIn = false;
				errorHandler( e.data.message );
			
			});		
		};

		function createUser() {

			factory.creating = true;
			
			auth.postUserCreate( factory.newUser )
				.then(function(r){
				
				successHandler(r.data.user, r.data.token);
			
			}, function(e) {

				factory.creating = false;
				errorHandler( JSON.parse(e.data.message) );
				factory.authError = true;

			});
		};

		function successHandler(user, token) {

			motionObj.getMotions();
			
			$rootScope.authenticatedUser = user;
			
			afterauth.setLoginAuthDetails(user, token);

			localStorage.setItem('public_computer', factory.publicComputer);
		}


		function errorHandler(message) {
			for (var i in factory.errors) 
				factory.errors[i] = false;

			if( message == "Invalid credentials" )
				factory.errors.invalidCredentials = true;
			else if(message == "Email address not in database")
				factory.errors.invalidEmail = true;
			else if(angular.isString(message) && message.substr(0, 17) == 'Account is locked')
				factory.errors.accountLocked = true;
			else if( message.hasOwnProperty('email') )
				if( message.email[0] == "validation.unique" )
					factory.errors.emailNotValid = true;
			else
				ToastMessage.report_error(message);
		};

		return factory;
	}


})();