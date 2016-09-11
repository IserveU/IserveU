(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.factory('authResource', ['$auth', '$http', '$sanitize', '$q', authResource]);

	function authResource($auth, $http, $sanitize, $q) {

		/****************************************************************
		*
		*	Resource functions to access API endpoints
		*
		*****************************************************************/

		var login = function(credentials) {
			return $auth.login(sanitizeCredentials(credentials)).then(function(user) {
				return user;
			}, function(error) {
				return $q.reject(error);
			});
		}

		var logout = function() {
			return $auth.logout();
		};

		var register = function(credentials){
			return $http.post('api/user', credentials).success(function(result) {
				return result;
			}).error(function(error) {
			  	return error;
			});
		}

		var rememberToken = function(remember_token) {
			return $http.get('authenticate/' + remember_token).success(function(result) {
				return result;
			}).error(function(error) {
				return error;
			});
		}

		var resetPassword = function(credentials) {
			return $http.post('authenticate/resetpassword', credentials).success(function(result) {
				return result;
			}).error(function(error){
				return error;
			});
		}

	    /*****************************************************************
	    *
	    *	Private Functions
	    *
	    ******************************************************************/
	    
		function sanitizeCredentials(credentials) {
			return {
		    	email: $sanitize(credentials.email),
		    	password: $sanitize(credentials.password),
		  	}
		}

		return {
			login: login,
			logout: logout,
			register: register,
			rememberToken: rememberToken,
			resetPassword: resetPassword
		};
	}


})();

