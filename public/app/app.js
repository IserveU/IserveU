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
		.config(['$urlRouterProvider', '$authProvider', '$compileProvider',
			function($urlRouterProvider, $authProvider, $compileProvider) {

				$compileProvider.debugInfoEnabled(false); // speeds up the app, the debug info are for {{}}

				$authProvider.loginUrl = '/authenticate';

			    // the overall default route for the app. If no matching route is found, then go here
			    $urlRouterProvider.otherwise('/home');			
				$urlRouterProvider.when("/user/:id", "/user/:id/profile"); // for displaying sub-url

		}])
		.run(['$rootScope', '$auth', '$window', 'redirect', 'globalService',
			function($rootScope, $auth, $window, redirect, globalService) {
				
				$rootScope.$on('$stateChangeStart', function(event, toState, toParams, fromState, fromParams) {	

					redirect.onLogin(toState, toParams, fromState);

					redirect.ifNotAuthenticated(
								event,
								toState.data.requireLogin,
								$auth.isAuthenticated(),
								toState.name,
								fromState.name
							);

					globalService.checkUser();

					globalService.setState( toState );
				});


		        globalService.init();

				$window.onbeforeunload = function(e) {
					var publicComputer = localStorage.getItem('public_computer');
					if(JSON.parse(publicComputer) == true) 
						return localStorage.clear();
				};

		}])

		
}());