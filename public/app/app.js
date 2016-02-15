(function() {

	'use strict';

	angular
		.module('iserveu', [
			'ngCookies',
			'ngResource',
			'ngMaterial',
			'ngMessages',
			'ngSanitize', 
			'satellizer',
			'textAngular',
			'ui.router',
			'flow',
            'infinite-scroll',
			'pascalprecht.translate',
			'mdColorPicker'
		])
		.config(function($provide, $urlRouterProvider, $httpProvider, $authProvider, $compileProvider) {

			$authProvider.loginUrl = '/authenticate';
			// speeds up the app, the debug info are for {{}}
			$compileProvider.debugInfoEnabled(false);

			$httpProvider.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

			$httpProvider.interceptors.push(function($timeout, $q, $injector, $rootScope) {

				var $state, $http;

				$timeout(function() {
					$http = $injector.get('$http');
					$state = $injector.get('$state');
				});

				return {
					responseError: function(rejection) {
						//this is way too explicit, 400 errors return on a lot.
						if(rejection.status === 400) {
							// $rootScope.userIsLoggedIn = false;
							// localStorage.clear();
							// if(!localStorage.satellizer_token){$state.go('login');}
						}
						if(rejection.status === 401){
							// $state.go('permissionfail');
						}
						return $q.reject(rejection);
					}
				}
			});

		    // the overall default route for the app. If no matching route is found, then go here
			$urlRouterProvider.when("/user/:id", "/user/:id/profile");

		    $urlRouterProvider.otherwise('/home');
  	    
		})
		.filter('dateToDate', function() {
		  	return function(input) {
		    	input = new Date(input);
		    	return input;
	  		};
		})

		.filter('proComment', function() {
			return function(input) {
				var out = [];
				for(var i = 0; i < input.length; i++) {
					if(input[i].position == "1") {
						out.push(input[i])
					}				
				}
				return out;
			}
		})
		.filter('conComment', function() {
			return function(input) {
				var out = [];
				for(var i = 0; i < input.length; i++) {
					if(input[i].position == "0" || input[i].position == "-1") {
						out.push(input[i])
					}				
				}
				return out;
			}
		})
		.filter('object2Array', function() {
		    return function(obj) {
		    	return Object.keys(obj).map(function(key){return obj[key];});
		    }
	 	})
	 	.filter('bytes', function() {
			return function(bytes, precision) {
				if (isNaN(parseFloat(bytes)) || !isFinite(bytes)) return '-';
				if (typeof precision === 'undefined') precision = 1;
				var units = ['bytes', 'kB', 'MB', 'GB', 'TB', 'PB'],
					number = Math.floor(Math.log(bytes) / Math.log(1024));
				return (bytes / Math.pow(1024, Math.floor(number))).toFixed(precision) +  ' ' + units[number];
			}
		})
		.run(function($rootScope, $auth, $state, $window, auth, pageObj) {

			// runs everytime a state changes
			$rootScope.$on('$stateChangeStart', function(event, toState, toParams, fromState, fromParams) {	
				if(toState.name !== 'login'){
					if(toState.name !== 'login.resetpassword'){
						$rootScope.redirectUrlName = toState.name;
						$rootScope.redirectUrlID = toParams.id;
						$rootScope.previousUrlID = fromParams.id;
					}
				}

				var requireLogin = toState.data.requireLogin;
				var auth = $auth.isAuthenticated();
				if(auth === false && requireLogin === true){
					event.preventDefault();
					if(fromState.name !== 'login' || toState.name !== 'login'){
						$state.go('login');
					}
				}

				var user = JSON.parse(localStorage.getItem('user'));
				if(user) {
					$rootScope.authenticatedUser = user;
					$rootScope.userIsLoggedIn = true;
				}
			    $rootScope.currentState = toState.name;	// used for sidebar directive
			});

			// runs once on app start
			$rootScope.themename = 'default';
	        $rootScope.motionIsLoading = [];

			$window.onbeforeunload = function(e) {
				var publicComputer = localStorage.getItem('public_computer');
				if(JSON.parse(publicComputer) == true) {
					return localStorage.clear();
				}
			}

		})

			


}());