(function() {

	'use strict';

	angular
		.module('iserveu', ['ngResource', 'ngMaterial', 'ui.router', 'ngSanitize'])
		.config(function($provide, $stateProvider, $urlRouterProvider, $httpProvider,$mdThemingProvider) {

			$mdThemingProvider.definePalette('amazingPaletteName', {
			    '50': 'ffffff',
			    '100': '99949D',
			    '200': '99949D',
			    '300': '99949D',
			    '400': '38353B',
			    '500': '38353B',
			    '600': '38353B',
			    '700': '1C1126',
			    '800': '1C1126',
			    '900': '1C1126',
			    'A100': 'ff0000',
			    'A200': 'ff0000',
			    'A400': 'ff0000',
			    'A700': 'ff0000',
			    'contrastDefaultColor': 'light',    
			    'contrastDarkColors': ['50', '100', '200', '300', '400', 'A100'],
			    'contrastLightColors': undefined    // could also specify this if default was 'dark'
			});
			$mdThemingProvider.theme('default').primaryPalette('amazingPaletteName');
			

			$urlRouterProvider.otherwise('/home');
			
			$stateProvider
				.state('home', {
					url: 'home',
					templateUrl: 'app/components/home/homeView.html',
					controller: 'homeController as home',
					data: {
						requireLogin: false
					}
				})
				.state('motion', {
					url: 'motion/:motionId',
					templateUrl: 'app/components/motion/motionView.detail.html',
					controller: 'motionController as motion',
					data: {
						requireLogin: false
					}
				})
				.state('profile', {
					url: 'profile',
					templateUrl: 'app/components/user/profileView.html',
					controller: 'userController as user',
					data: {
						requireLogin: true
					}
				})
				.state('login', {
					url: 'login',
					templateUrl: 'app/components/login/loginView.html',
					controller: 'loginController as login'
				});

			$httpProvider.interceptors.push(function ($timeout, $q, $injector) {
			    var loginModal, $http, $state;

			    // this trick must be done so that we don't receive
			    // `Uncaught Error: [$injector:cdep] Circular dependency found`
			    $timeout(function () {
			     // loginModal = $injector.get('loginModal'); switch to a non modal login
			      $http = $injector.get('$http');
			      $state = $injector.get('$state');
			    });

			    return {
			      responseError: function (rejection) {
			        if (rejection.status !== 401) {
			          return rejection;
			        }

			        var deferred = $q.defer();

			    /*	loginModal()
			          .then(function () {
			          	deferred.resolve( $http(rejection.config) );
			          })
			          .catch(function () {
			            $state.go('welcome');
			            deferred.reject(rejection);
			          }); */

			        return deferred.promise;
			      }
			    };
			  });
			
		})
		.run(function($rootScope, $state, /*loginModal,*/ auth) {

			auth.isLoggedIn().success(function(user) {
				if(user != "not logged in") {
					$rootScope.userIsLoggedIn = true;
					$rootScope.currentUser = user;
				}
				else {
					$rootScope.userIsLoggedIn = false;
					$rootScope.currentUser = undefined;
				}
			});

			$rootScope.$on('$stateChangeStart', function(event, toState, toParams) {
				var requireLogin = toState.data.requireLogin;

				if(requireLogin && typeof $rootScope.currentUser === 'undefined') {
					event.preventDefault();

				/*	loginModal().then(function() {
						return $state.go(toState.name, toParams);
					})
					.catch(function() {
						return $state.go('home');
					}); */
				}
			})
		});
})();