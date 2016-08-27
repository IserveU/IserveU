(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('afterauth', [
		'$stateParams', 
		'$state', 
		'$rootScope', 
		'auth', 
		'user', 
		'motionIndex',
			afterauth]);

  	 /** @ngInject */
	function afterauth($stateParams, $state, $rootScope, auth, user, motionIndex) {


		function clearCredentials(){
			localStorage.clear();
			$rootScope.authenticatedUser = null;
			$rootScope.userIsLoggedIn = false;
			motionIndex.clear();
		}

		function redirect(){

			// @deprecated and moved to func: setLoginAuthDetails
			// $rootScope.userIsLoggedIn = true;

			return $rootScope.redirectUrlName 
				   ? $state.transitionTo($rootScope.redirectUrlName, {"id": $rootScope.redirectUrlID}) 
				   : $state.transitionTo('home');
		}

		 function setLoginAuthDetails (user, token, resetPassword){
			if(token) localStorage.setItem( 'satellizer_token', JSON.stringify( token ) );

			localStorage.setItem( 'user', JSON.stringify(user) );
			$rootScope.authenticatedUser = user;
			$rootScope.userIsLoggedIn = true;

			if(resetPassword)
				$state.transitionTo('edit-user', {id: user.id});
			else
				redirect();
		}


		return {
			clearCredentials: clearCredentials,
			redirect: redirect,
			setLoginAuthDetails: setLoginAuthDetails
		}


	}
})();
