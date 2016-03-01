(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('loginService', loginService);

  	 /** @ngInject */
	function loginService($rootScope, auth, afterauth, ToastMessage) {

		var loginObj = {
			creating: false,
			loggingIn: false,
			publicComputer: false,
			credentials: { email: '', password: '' },
			newUser: { first_name: '',
					   last_name: '',
				       email: '',
					   password: '' },
			errors: { emailNotValid: false,
					  invalidCredentials: false,
					  invalidEmail: false,
					  accountLocked: false },
			createUser: createUser,
			login: login,
		};

		function login(credentials) {

			loginObj.loggingIn = true;

			auth.login( credentials )
				.then(function(r) {

				$rootScope.authenticatedUser = r.data.user;
				setLocalStorage( credentials );
			
			}, function(e) {

				loginObj.loggingIn = false;
				errorHandler( e.data.message );
			
			});		
		};

		function createUser() {

			loginObj.creating = true;
			
			auth.postUserCreate( loginObj.newUser )
				.then(function(r){

				loginObj.creating = false;
				
				login({email: loginObj.newUser.email, 
					   password:loginObj.newUser.password} );
			
			}, function(e) {

				loginObj.creating = false;
				errorHandler( JSON.parse(e.data.message) );

			});
		};

		function setLocalStorage(credentials) {

			auth.postAuthenticate( credentials )
				.then(function(r) {
				
				afterauth.setLoginAuthDetails(r.data.user);
				localStorage.setItem('public_computer', loginObj.publicComputer);
			
			});
		};


		function errorHandler(message) {
			for (var i in loginObj.errors) 
				loginObj.errors[i] = false;

			if( message == "Invalid credentials" )
				loginObj.errors.invalidCredentials = true;
			else if(message == "Email address not in database")
				loginObj.errors.invalidEmail = true;
			else if(angular.isString(message) && message.substr(0, 17) == 'Account is locked')
				loginObj.errors.accountLocked = true;
			else if( message.hasOwnProperty('email') )
				if( message.email[0] == "validation.unique" )
					loginObj.errors.emailNotValid = true;
			else
				ToastMessage.report_error(message);
		};

		return loginObj;
	}


})();