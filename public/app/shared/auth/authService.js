(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('auth', auth);

	function auth($resource, $http, $sanitize, session, CSRF_TOKEN) {

		var loginError = function(response) {
			
			console.log(response);
		};

		var sanitizeCredentials = function(credentials) {
			return {
		    	email: $sanitize(credentials.email),
		    	password: $sanitize(credentials.password),
		    	csrf_token: CSRF_TOKEN
		  	};
		};

		return {
			login: function(credentials) {

				console.log(credentials);
		    	var login = $http.post("/auth/login", sanitizeCredentials(credentials));

		    	return login;
		  	},
		  	logout: function() {
		  		console.log('logout');
		    	var logout = $http.get("/auth/logout");
		    	return logout;
		  	},
		  	isLoggedIn: function() {
		    	var isLoggedIn = $http.get("api/user/loggedin");
		    	return isLoggedIn;
		  	}
		};
	}

	
})();