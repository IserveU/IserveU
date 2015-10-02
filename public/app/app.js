(function() {

	'use strict';

	angular
		.module('iserveu', [
			'ngResource',
			'ngMaterial',
			'ui.router',
			'ngSanitize', 
			'satellizer',
			'textAngular',
			'flow',
			'formly',
			'ngMessages',
			'pascalprecht.translate',
			'ngCookies'
		])
		.config(function($provide, $urlRouterProvider, $httpProvider, $authProvider, $compileProvider) {

			// speeds up the app, the debug info are for {{}}
			$compileProvider.debugInfoEnabled(false);

			$httpProvider.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

			$authProvider.loginUrl = '/authenticate';

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
			
			$urlRouterProvider.when('/', ['$state', '$match', function($state, $match) {
				$state.go('home');
			}])	

			$urlRouterProvider.when("/motion/:id", "/motion/:id/");
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
		.run(function($rootScope, $auth, $state, auth) {

			$rootScope.themename = 'default';

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
			    //this is slowing down app more than $watch in directives, however that flash is super annoying
			    if(toState.name === 'myprofile' || 'createuser'){
			    	$rootScope.currentState = 'user';
			    }
			    if(toState.name === 'home'){
			    	$rootScope.currentState = 'motion';
			    }
			    if(toState.name === 'createmotion'){
			    	$rootScope.currentState = 'motion';
			    }
			    if(toState.name === 'motion.components'){
			    	$rootScope.currentState = 'motion';
			    }
			});		

		})
    .controller('AppCtrl', function($scope) {
      $scope.isOpen = false;
      $scope.demo = {
        isOpen: false,
        count: 0,
        selectedAlignment: 'md-left'
      };
    });




	// var AppController = function($scope, $mdUtil, $mdSidenav, $log) {

	// 	$scope.toggleSidebar = buildToggler('sidebar');
    	
 	//	$scope.toggleUserbar = buildToggler('user-bar');
	    
	//  function buildToggler(navID) {
	    	
	//       var debounceFn = $mdUtil.debounce(function(){
	//             $mdSidenav(navID)
	//               .toggle()
	//               .then(function () {
	//                 $log.debug("toggle " + navID + " is done");
	//               });
	//           },300);
	//       return debounceFn;
	//     }
	// };
	
	// AppController.$inject = module.controller.injectables;

	
			


}());