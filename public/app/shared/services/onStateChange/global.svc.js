(function() {

	'use strict';

	angular
		.module('iserveu')
		.service('globalService', globalService);

	/** @ngInject */
	function globalService($rootScope, incompleteProfileService, SETTINGS_JSON) {

		/**
		*	Initializes global variables.
		*
		*/
		this.init = function() {
			$rootScope.userIsLoggedIn = false;
			$rootScope.authenticatedUser = null;
			$rootScope.settingsGlobal = SETTINGS_JSON;
			$rootScope.themename = SETTINGS_JSON.themename;
		};

		/**
		*	Checks that the user's credentials are set up in the local storage.
		*	Assigns global variables that are checked in the view model
		*   throughout the app.
		*/
		this.checkUser = function() {
			var user = JSON.parse(localStorage.getItem('user'));
			
			if(user) {
				$rootScope.authenticatedUser = user;
				$rootScope.userIsLoggedIn = true;
				$rootScope.incompleteProfile = incompleteProfileService.check(user);
			};
		};

		/**
		*	Points current state name to a rootScope variable that is 
		*	accessed throughout the app for the sidebar directive 
		*	which dynamically renders the state's sidebar.
		*/
		this.setState = function(state) {
			$rootScope.currentState = state.name;
		};


	}

})();