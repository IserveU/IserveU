(function() {

	'use strict';

	angular
		.module('iserveu')
		.service('redirectService', ['$rootScope', '$state', redirectService]);

	/** @ngInject */
	function redirectService($rootScope, $state) {

		/**
		*	Redirect function for when a user is forwarded to a site URL and
		*	logs in. They will be redirected the previous state they were
		*	at before being rejected by authentication.
		*/
		this.onLogin = function(state, params, prevState) {
			if(state.name !== 'login')
				if (state.name !== 'login.resetpassword') {
				$rootScope.redirectUrlName = state.name;
				$rootScope.redirectUrlID = params.id;
				$rootScope.previousUrlID = prevState.id;
			};
		};
	
		/**
		*	Redirect when a user is not authenticated via AuthController
		*	or they have somehow lost their localstorage credentials. Logs the user
		*	out and redirects them to login state.
		*/
		this.ifNotAuthenticated = function(ev, requireLogin, auth, state, prevState) {
			if(auth === false && requireLogin === true){
				ev.preventDefault();
				if(prevState !== 'login' || state !== 'login')
					$state.go('login');
			};
		}; 

		/**
		 * Basic redirect if there has been a login. It will take the user to their previous
		 * state before they were required to login. Or if they were given a URL they did
		 * not have permissions to.
		 * @return {stateChange}  Previously visted state.
		 */
		this.redirect = function() {
			return $rootScope.redirectUrlName 
			   ? $state.transitionTo($rootScope.redirectUrlName, {"id": $rootScope.redirectUrlID}) 
			   : $state.transitionTo('home');
		}

		this.onLogout = function() {
			$state.transitionTo('login');
		}

	}

})();