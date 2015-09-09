(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('auth', auth);

	function auth($resource, $http, $sanitize, CSRF_TOKEN, $auth, $q) {

		var login = function(credentials) {
			return $auth.login(sanitizeCredentials(credentials)).then(function(user) {
				return user;
			}, function(error) {
				return $q.reject(error);
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
		};

		var getNoPassword = function(remember_token) {
			return $http.get('authenticate/' + remember_token).success(function(result) {
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
			  	return $q.reject(error);
			});
		};

		var getSettings = function() {
			return $http.get('settings').success(function(result) {
				return result;
			}).error(function(error) {
				return error;
			});
		};

		var getResetPassword = function(credentials) {
			return $http.post('authenticate/resetpassword', credentials).success(function(result) {
				return result;
			})
			.error(function(error){
				return error;
			})
		}

		return {
			login: login,
		  	logout: logout,
		  	getAuthenticatedUser: getAuthenticatedUser,
		  	postAuthenticate: postAuthenticate,
		  	getNoPassword: getNoPassword,
		  	postUserCreate: postUserCreate,
		  	getSettings: getSettings,
		  	getResetPassword: getResetPassword
		};
	}

	
})();