/**
 * @description
 * @name isuApiProvider
 */

/** @ngInject*/
angular.module('iserveu')
.provider('$globalProvider', ['SETTINGS_JSON', function(SETTINGS_JSON) {
	'use strict';

	this.$get = ['$injector', '$http', function($injector, $http) {
		return {
			/**
			*	Initializes global variables.
			*
			*/
			init: function() {
	            var $rootScope = $injector.get('$rootScope');
				$rootScope.userIsLoggedIn = false;
				$rootScope.authenticatedUser = null;
				$rootScope.settingsGlobal = SETTINGS_JSON;
				$rootScope.themename = SETTINGS_JSON.themename;
				$rootScope.taDropHandler = this.dropHandler;
			},

			/**
			*	Global file drop uploader to textAngular.
			*
			*/
			dropHandler: function(file, insertAction){
				var fileService = $injector.get('fileService');
				var reader = new FileReader();
				if(file.type.substring(0, 5) === 'image'){
					reader.onload = function() {
						if(reader.result !== '')
							fileService.upload(file).then(function(r){
								console.log(r);
								insertAction('insertImage', '/uploads/'+r.data.filename, true);
							}, function(e) { console.log(e); });
					};

					reader.readAsDataURL(file);
					return true;
				}
				return false;
			},

			/**
			*	Checks that the user's credentials are set up in the local storage.
			*	Assigns global variables that are checked in the view model
			*   throughout the app.
			*/
			checkUser: function() {
	            var $rootScope = $injector.get('$rootScope');
	            var incompleteProfileService = $injector.get('incompleteProfileService');

				var user = JSON.parse(localStorage.getItem('user'));
				
				if(user) {
					$rootScope.authenticatedUser = user;
					$rootScope.userIsLoggedIn = true;
					$rootScope.incompleteProfile = incompleteProfileService.check(user);
				};
			},


			/**
			*	Checks state permissions against the user's permissions that have
			*	been defined on login. Redirects them to the home page if 
			*   they do not have all the required permissions.
			*/
			checkPermissions: function(ev, requirePermissions) {
				
				var $state = $injector.get('$state');
				var $rootScope = $injector.get('$rootScope');
				var Authorizer = $injector.get('Authorizer');
	            
	            var authorized = Authorizer.canAccess(requirePermissions);
				
				if(authorized === false){
					ev.preventDefault();
					$state.go('home');
					$rootScope.pageLoading = false;
				}
			},

			/**
			*	Points current state name to a rootScope variable that is 
			*	accessed throughout the app for the sidebar directive 
			*	which dynamically renders the state's sidebar.
			*/
			setState: function(state) {
	            var $rootScope = $injector.get('$rootScope');	
				
				$rootScope.currentState = state.name;
				$rootScope.isLoginState = state.name.substr(0,5) === 'login';
			}


		}

	}];




}]);

