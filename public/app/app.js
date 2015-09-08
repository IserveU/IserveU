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
			'ngMessages'
		])
		.config(function($provide, $stateProvider, $urlRouterProvider, $httpProvider, $authProvider, formlyConfigProvider) {
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
						return $q.reject(rejection);
					}
				}
			});

		    // the overall default route for the app. If no matching route is found, then go here
			
			$urlRouterProvider.when('/', ['$state', '$match', function($state, $match) {
				$state.go('home');
			}])	

		    $urlRouterProvider.otherwise('/home');
				


		    formlyConfigProvider.disableWarnings = true;
		    formlyConfigProvider.setType({
			  name: 'input',
			  template: '<md-input-container><label>{{options.templateOptions.label}}</label><input ng-model="model[options.key]"></md-input-container>'
			});
			formlyConfigProvider.setType({
			  name: 'date',
			  template: '<md-input-container><label>{{options.templateOptions.label}}</label><input type="date" ng-model="model[options.key]"></md-input-container>'
			});
			formlyConfigProvider.setType({
			  name: 'md-switch',
			  template: '<md-switch ng-model="model[options.key]" ng-value="options.templateOptions.valueProp" ng-true-value="1" ng-false-value="0"/>{{options.templateOptions.label}}'
			});
			formlyConfigProvider.setType({
			  name: 'email',
			  template: '<md-input-container><label>{{options.templateOptions.label}}</label><input type="email" ng-model="model[options.key]"></md-input-container>'
			});
			formlyConfigProvider.setType({
			  name: 'password',
			  template: '<md-input-container><label>{{options.templateOptions.label}}</label><input type="password" ng-hide="true" ng-model="options.templateOptions.valueProp"/></md-input-container>'
			});
			formlyConfigProvider.setType({
			  name: 'password-edit',
			  template: '<md-button type="submit" ng-click="options.templateOptions.ngClick()"><label>Change password</label></md-button>'
			});
			formlyConfigProvider.setType({
			  name: 'select',
			  template: '<md-select aria-label="select" ng-model="model[options.key]" placeholder="Ethnic Origins"><md-option ng-repeat="item in options.templateOptions.ngRepeat" ng-value="item.id">{{item.region}}<md-tooltip>{{item.description}}</md-tooltip></md-option></md-select>'
			});
			formlyConfigProvider.setType({
			  name: 'userform',
			  templateUrl: 'app/components/user/userform.tpl.html'
			});


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
		    	    url: '/user/:id',
		    	    templateUrl: 'app/components/user/user.tpl.html',
		    	    controller: 'UserController as user',
		    	    data: {
		    	        requireLogin: true
		    	    }
		    	})
		    	.state( 'myprofile', {
		    	    url: '/myprofile',
		    	    templateUrl: 'app/components/user/user.tpl.html',
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
	        	})
	        	.state('backgroundimage.preview', {
	                url: '^/preview/:id',
	            	controller: 'BackgroundImageController as preview',
	            	templateUrl: 'app/components/backgroundimage/preview_image.tpl.html',
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
					if(toState.name !== 'login.resetpassword'){
						$rootScope.redirectUrlName = toState.name;
						$rootScope.redirectUrlID = toParams.id;
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
			    if($rootScope.currentState == 'myprofile'){
			    	$rootScope.currentState = 'user';
			    }
			    if($rootScope.currentState == 'home'){
			    	$rootScope.currentState = 'motion';
			    }
			    if($rootScope.currentState == 'createmotion'){
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