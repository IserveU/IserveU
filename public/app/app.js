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

			// speeds up the app, the debug info are for {{}}
			$compileProvider.debugInfoEnabled(false);

			$httpProvider.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

			//being unused ... it's a good concept that will come in handy later as an error handler
			// as well as a trigger for 200 triggers!

			// $httpProvider.interceptors.push(function($timeout, $q, $injector, $rootScope) {
			// 	var $state, $http;

			// 	$timeout(function() {
			// 		$http = $injector.get('$http');
			// 		$state = $injector.get('$state');
			// 	});
			// 	return {
			// 		responseError: function(rejection) {
			// 			//this is way too explicit, 400 errors return on a lot.
			// 			if(rejection.status === 400) {
			// 				// $rootScope.userIsLoggedIn = false;
			// 				// localStorage.clear();
			// 				// if(!localStorage.satellizer_token){$state.go('login');}
			// 			}
			// 			if(rejection.status === 401){
			// 				// $state.go('permissionfail');
			// 			}
			// 			return $q.reject(rejection);
			// 		}
			// 	}
			// });

		    // the overall default route for the app. If no matching route is found, then go here
			$urlRouterProvider.when("/user/:id", "/user/:id/profile");

		    $urlRouterProvider.otherwise('/home');

			$authProvider.loginUrl = '/authenticate';
  	    
		})
		.run(function($rootScope, $auth, $state, $window, $http, auth) {

			// runs everytime a state changes
			$rootScope.$on('$stateChangeStart', function(event, toState, toParams, fromState, fromParams) {	
				
				// for redirects on fails
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

				// make sure user is logged in each time
				var user = JSON.parse(localStorage.getItem('user'));
				if(user) {
					$rootScope.authenticatedUser = user;
					$rootScope.userIsLoggedIn = true;
				}

			    $rootScope.currentState = toState.name;	// used for sidebar directive


			    if(toState.data.motionModule) {
			    	console.log('motionModule===true');
			    }

				$http.defaults.headers.common['X-CSRFToken'] = localStorage.getItem('satellizer_token');

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