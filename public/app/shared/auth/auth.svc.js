(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('auth', auth);

	function auth($resource, $http, $sanitize, CSRF_TOKEN, $auth) {

		var login = function(credentials) {
			return $auth.login(sanitizeCredentials(credentials)).then(function(user) {
				return user;
			}, function(error) {
				return error;
			});
		};

		var logout = function() {
			return $auth.logout();
		};

		var sanitizeCredentials = function(credentials) {
			return {
		    	email: $sanitize(credentials.email),
		    	password: $sanitize(credentials.password),
		    	csrf_token: CSRF_TOKEN
		  	};
		};

		var getAuthenticatedUser = function() {
			return $http.get('api/user/authenticateduser').success(function(result) {
				return result;
			}).error(function(error) {
				return error;
			});
		};
		
		var postAuthenticate = function(credentials) {
			return $http.post('authenticate', credentials).success(function(result) {
				return result;
			}).error(function(error) {
				return error;
			})
		}


		var postUserCreate = function(credentials) {
			return $http.post('api/user', credentials).success(function(result) {					
				return result;
			})
			  .error(function(error) {
			  	return error;
			});
		};

		return {
			login: login,
		  	logout: logout,
		  	getAuthenticatedUser: getAuthenticatedUser,
		  	postAuthenticate: postAuthenticate,
		  	postUserCreate: postUserCreate
		};
	}

	
})();