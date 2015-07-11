(function() {

	'use strict';

	angular
		.module('iserveu', [
			'ngResource',
			'ngMaterial',
			'ui.router',
			'ngSanitize', 
			'satellizer'
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
							$state.go('login');
						}
						return $q.reject(rejection);

					// 	var deferred = $q.defer();
					// 	if(rejection.status === 200) {
					// 		return deferred.promise;
					// 	}
					// 	else
					// 		return $q.reject(rejection);		
					// }
				}
			}
			});

		    // the overall default route for the app. If no matching route is found, then go here
		    $urlRouterProvider.otherwise('/home');

		    $stateProvider
		    	.state( 'home', {
		    		url: '/home',
		    		templateUrl: 'app/components/home/home.tpl.html',
		    		controller: 'HomeController as home'
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
		    	    url: '/:id',
		    	    templateUrl: 'app/components/user/userprofile.tpl.html',
		    	    controller: 'UserController as user',
		    	    data: {
		    	        requireLogin: true
		    	    }
		    	})	
		    	.state( 'sidebar', {
		    		templateUrl: 'app/shared/sidebar/sidebar.tpl.html',
		    		controller:'SidebarController as sidebar',
		    		views: {
		    			'motionsidebar': {
		    				templateUrl: 'app/shared/sidebar/sidebar.tpl.html',
		    				controller:'SidebarController as sidebar',
		    			    data: {
		    	      			 requireLogin: true
		    	   			}
		    			}
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
	        	.state('backgroundimage', {
	                url: '/upload',
	            	controller: 'BackgroundImageController as backgroundimage',
	            	templateUrl: 'app/shared/backgroundimage/backgroundimage.tpl.html',
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
		.filter('publicProfile', function() {
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
		.directive('backImg', function(){
		    return function(scope, element, attrs){
		        var imgnum =  Math.floor(Math.random() * (18 - 1 + 1)) + 1;
		        if(imgnum<=9){
		        	imgnum = "0"+imgnum;

		        }

		        element.css({
		            'background-image':  "url(/themes/<?=config('app.themename')?>/photos/large/" + imgnum + ".jpg)",
		            'background-size' : 'cover'
		        });
		    };
		})
		.run(function($rootScope, $auth, $state, auth) {
			
			$rootScope.$on('$stateChangeStart', function(event, toState, toParams, fromState, fromParams) {		       
				var user = JSON.parse(localStorage.getItem('user'));
					if(user) {
					
						$rootScope.authenticatedUser = user;
						$rootScope.userIsLoggedIn = true;
						if(toState.name === 'login') {
							event.preventDefault();
							//$state.go('home');
						}
						if(toState.name === 'createmotion' && $rootScope.createMotion === false) {
							event.preventDefault();
							$state.go('home');
						}
						if(toState.name === 'user' || toState.name === 'user.profile') {
							$rootScope.$emit('userListIsClicked');
						}
					}



			    var authenticated = $auth.isAuthenticated();


			    // $state.go('login', {})

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