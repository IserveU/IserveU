(function() {

	'use strict';

  	var module = {
	    name: 'iserveu',
	    dependencies: [
	        'ngResource', 'ngMaterial', 'ui.router', 'ngSanitize','iserveu.home','iserveu.sidebar', 'iserveu.motion'
	    ],
	    config: {
	        providers: ['$provide', '$stateProvider', '$urlRouterProvider', '$httpProvider','$mdThemingProvider']
	    },
	    controller: {
	        name: 'AppController',
	        injectables: []
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
	            controller: module.controller.name + ' as app'
	    });

	          

			$mdThemingProvider.definePalette('iServeUPalette', {
			    '50': '006e73',
			    '100': '006e73',
			    '200': '006e73',
			    '300': '006e73',
			    '400': '00acb1',
			    '500': '00acb1',
			    '600': '00acb1',
			    '700': 'ff7600',
			    '800': 'ff7600',
			    '900': 'ff7600',
			    'A100': 'ff0000',
			    'A200': 'ff0000',
			    'A400': 'ff0000',
			    'A700': 'ff0000',
			    'contrastDefaultColor': 'light',    
			    'contrastDarkColors': ['50', '100', '200', '300', '400', 'A100'],
			    'contrastLightColors': undefined    
			});
			$mdThemingProvider.theme('default').primaryPalette('iServeUPalette').accentPalette('grey');
			

	
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



	var AppController = function() {
		 
		var vm = this;


		$scope.toggleSidebar = buildToggler('left-nav');
    	
    	$scope.toggleUserbar = buildToggler('user-bar');
	    
	    function buildToggler(navID) {
	    	
	      var debounceFn =  $mdUtil.debounce(function(){
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
		.controller(module.controller.name, AppController);
	}());


	

/*


			$urlRouterProvider.otherwise('/home');
			
			$stateProvider
				.state('home', {
					abstract: true,
					url: 'home',
					views: {
						'body': {
							'templateUrl': 'index.tpl.html'
						}
					},
					data: {
						requireLogin: false
					},
					controller: 'homeController as home',

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
			
		})
		.run(function($rootScope, $state, auth) {

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

				
				}
			})
		});
 
	

})();*/