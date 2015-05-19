(function() {

	'use strict';

  	var module = {
	    name: 'iserveu',
	    dependencies: [
	        'ngResource', 'ngMaterial', 'ui.router', 'ngSanitize','iserveu.home','iserveu.sidebar', 'iserveu.motion', 'iserveu.event'
	    ],
	    config: {
	        providers: ['$provide', '$stateProvider', '$urlRouterProvider', '$httpProvider','$mdThemingProvider']
	    },
	    controller: {
	        name: 'AppController',
	        injectables: ['$scope', '$mdUtil', '$mdSidenav', '$log']
	    }
	};

	var AppConfig = function($provide, $stateProvider, $urlRouterProvider, $httpProvider,$mdThemingProvider) {

	    // the overall default route for the app. If no matching route is found, then go here
	    $urlRouterProvider.otherwise('/app/home');

	    $stateProvider
	        .state('app', {
	            abstract: true,
	            url: '/app',
	            views: {
	                'body': {
	                    templateUrl: 'app/index.tpl.html'
	                }
	            },
	            data: {
	            	requireLogin: false
	            },
	            controller: module.controller.name + ' as app'
	    	})

	    	/*
	    	.state('login', {
                url: '/login',
            	controller: 'loginController as login',
                views: {
	                'body': {
	                    templateUrl: 'app/fullscreen.tpl.html'
	                }
                },
                data: {
                    requireLogin: false
                } 
        	});	     */      

		/* $mdThemingProvider.definePalette('isuAqua', {
		    '50': '61d3d8',
		    '100': '61d3d8',
		    '200': '61d3d8',
		    '300': '61d3d8',
		    '400': '00acb1',
		    '500': '00acb1',
		    '600': '00acb1',
		    '700': '006e73',
		    '800': '006e73',
		    '900': '006e73',
		    'A100': 'ff0000',
		    'A200': 'ff0000',
		    'A400': 'ff0000',
		    'A700': 'ff0000',
		    'contrastDefaultColor': 'light',    
		});
		$mdThemingProvider.definePalette('isuOrange', {
		    '50': 'ffb473',
		    '100': 'ffb473',
		    '200': 'ffb473',
		    '300': 'ffb473',
		    '400': 'ff7600',
		    '500': 'ff7600',
		    '600': 'ff7600',
		    '700': 'a64d00',
		    '800': 'a64d00',
		    '900': 'a64d00',
		    'A100': 'ffb473',
		    'A200': 'ff7600',
		    'A400': 'ff7600',
		    'A700': 'a64d00',
		    'contrastDefaultColor': 'light',    
		}); */


		$mdThemingProvider.definePalette('isuAqua', {
		    '50': '4E6829',
		    '100': '4E6829',
		    '200': '4E6829',
		    '300': '4E6829',
		    '400': '4E6829',
		    '500': '4E6829',
		    '600': '4E6829',
		    '700': '4E6829',
		    '800': '4E6829',
		    '900': '4E6829',
		    'A100': 'ff0000',
		    'A200': 'ff0000',
		    'A400': 'ff0000',
		    'A700': 'ff0000',
		    'contrastDefaultColor': 'light',    
		});
		$mdThemingProvider.definePalette('isuOrange', {
		    '50': '5F2640',
		    '100': '5F2640',
		    '200': '5F2640',
		    '300': '5F2640',
		    '400': '5F2640',
		    '500': '5F2640',
		    '600': '5F2640',
		    '700': '5F2640',
		    '800': '5F2640',
		    '900': '5F2640',
		    'A100': '5F2640',
		    'A200': '5F2640',
		    'A400': '5F2640',
		    'A700': '5F2640',
		    'contrastDefaultColor': 'light',    
		});

		$mdThemingProvider.theme('default').primaryPalette('isuAqua').accentPalette('isuOrange');

		$httpProvider.interceptors.push(function ($timeout, $q, $injector) {
		    var userBar, $http, $state;

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

		        return deferred.promise;

		      }
		    };
		});
	};

	AppConfig.$provide = module.config.providers;



	var AppController = function($scope, $mdUtil, $mdSidenav, $log) {

		$scope.toggleSidebar = buildToggler('sidebar');
    	
    	$scope.toggleUserbar = buildToggler('user-bar');
	    
	    function buildToggler(navID) {
	    	
	      var debounceFn = $mdUtil.debounce(function(){
	            $mdSidenav(navID)
	              .toggle()
	              .then(function () {
	                $log.debug("toggle " + navID + " is done");
	              });
	          },300);
	      return debounceFn;
	    }
	};
	
	AppController.$inject = module.controller.injectables;

	angular.module(module.name, module.dependencies)
		.config(AppConfig)		
		.controller(module.controller.name, AppController)
		.run(function($rootScope, $state, auth, $location) {

			auth.isLoggedIn().success(function(user) {
				if(user === "not logged in") {
					$rootScope.userIsLoggedIn = false;
					$rootScope.currentUser = undefined;
				}
				else {
					$rootScope.userIsLoggedIn = true;
					$rootScope.currentUser = user;
				}
			});

			$rootScope.$on('$stateChangeStart', function(event, toState, toParams) {

				// auth.isLoggedIn().success(function(user) {
				// 	if(user === "not logged in") {
				// 		$rootScope.userIsLoggedIn = false;
				// 		$rootScope.currentUser = undefined;
				// 		$location.path('/login');
				// 	}
				// 	else {
				// 		$rootScope.userIsLoggedIn = true;
				// 		$rootScope.currentUser = user;
				// 	}
				// });
				
				var requireLogin = toState.data.requireLogin;

				if(requireLogin && typeof $rootScope.currentUser === 'undefined') {					
					
					
				}
			});


		})
		.filter('dateToISO', function() {
		  	return function(input) {
		  		if(typeof input !== "undefined"){
		    		input = new Date(input).toISOString();
		    		return input;
		    	}
	  		};
		})
		.filter('proComment', function() {
			return function(input) {
				var out = [];
				for(var i = 0; i < input.length; i++) {
					if(input[i].position === "1") {
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
					if(input[i].position === "0" || input[i].position === "-1") {
						out.push(input[i])
					}				
				}
				return out;
			}
		});	


}());


	
