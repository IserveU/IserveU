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
		])
		.config(function($provide, $stateProvider, $urlRouterProvider, $httpProvider, $authProvider) {
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
							$rootScope.userIsLoggedIn = false;
							localStorage.clear();
							if(!localStorage.satellizer_token){$state.go('login');}
						}
						return $q.reject(rejection);
					}
				}
			});

		    // the overall default route for the app. If no matching route is found, then go here
		    $urlRouterProvider.otherwise('/home');

		    $stateProvider
		    	.state( 'home', {
		    		url: '/home',
		    		templateUrl: 'app/components/home/home.tpl.html',
		    		controller: 'HomeController as home',
		    		data: {
		    	        requireLogin: true
		    	    }
		    	})
		    	.state( 'motion', {
		    	    url: '/motion/:id',
		    	    templateUrl: 'app/components/motion/motion.tpl.html',
		    	    controller: 'MotionController as motion',
		    	    data: {
		    	        requireLogin: true
		    	    }
		    	})
		    	.state( 'createmotion', {
		    	    url: '/createmotion',
		    	    templateUrl: 'app/components/motion/createmotion/createmotion.tpl.html',
		    	    controller: 'CreateMotionController as createmotion',
		    	    data: {
		    	        requireLogin: true
		    	    }
		    	})

		    	.state( 'user', {
		    	    url: '/user',
		    	    templateUrl: 'app/components/user/user.tpl.html',
		    	    controller: 'UserController as user',
		    	    data: {
		    	        requireLogin: true
		    	    }
		    	})
		    	.state( 'user.profile', {
		    	    url: '^/profile/:id',
		    	    templateUrl: 'app/components/user/userprofile.tpl.html',
		    	    controller: 'UserController as user',
		    	    data: {
		    	        requireLogin: true
		    	    }
		    	}) 	
		    	.state('login', {
	                url: '/login',
	            	controller: 'loginController as login',
	            	templateUrl: 'app/shared/login/login.tpl.html',
	                data: {
	                    requireLogin: false
	                } 
	        	})
	        	.state('login.resetpassword', {
	        		url: '/:resetpassword',
	        		data: {
	        			requireLogin: false
	        		}
	        	})
	        	.state('department' , {
	        		url: '/departments/:id',
	            	controller: 'DepartmentController as department',
	            	templateUrl: 'app/shared/department/department.tpl.html',
	                data: {
	                    requireLogin: true
	                } 
	        	})
	        	.state('property' , {
	        		url: '/property',
	            	controller: 'PropertyController as property',
	            	templateUrl: 'app/shared/property/propertyassessment/propertyassessment.tpl.html',
	                data: {
	                    requireLogin: true
	                } 
	        	})
	        	.state('backgroundimage', {
	                url: '/upload',
	            	controller: 'BackgroundImageController as background',
	            	templateUrl: 'app/components/backgroundimage/backgroundimage.tpl.html',
	                data: {
	                    requireLogin: true
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
		.run(function($rootScope, $auth, $state, auth) {

			$rootScope.themename = 'default';

			$rootScope.$on('$stateChangeStart', function(event, toState, toParams, fromState, fromParams) {	
				
				if(toState.name !== 'login'){
					$rootScope.redirectUrlName = toState.name;
					$rootScope.redirectUrlID = toParams.id;
				}

				var requireLogin = toState.data.requireLogin;

				var auth = $auth.isAuthenticated();
					if(!auth && requireLogin){
						event.preventDefault();
						$state.go('login');
					}

				var user = JSON.parse(localStorage.getItem('user'));
					if(user) {
						$rootScope.authenticatedUser = user;
						$rootScope.userIsLoggedIn = true;
					}

			    $rootScope.currentState = toState.name;	// used for sidebar directive
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