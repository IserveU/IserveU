(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('afterauth', [
			'$rootScope', 
			'$state', 
			'motionIndex',
		afterauth]);

  	 /** @ngInject */
	function afterauth($rootScope, $state, motionIndex) {

		function clearCredentials(){
			$rootScope.authenticatedUser = null;
			$rootScope.userIsLoggedIn = false;
			
			localStorage.clear();
			motionIndex.clear();
		}

		function redirect(){
			return $rootScope.redirectUrlName 
				   ? $state.transitionTo($rootScope.redirectUrlName, {"id": $rootScope.redirectUrlID}) 
				   : $state.transitionTo('home');
		}

		 function setLoginAuthDetails (user, token, resetPassword){

			localStorage.setItem( 'api_token', token );
			localStorage.setItem( 'user', JSON.stringify(user) );

			$rootScope.authenticatedUser = user;
			$rootScope.userIsLoggedIn    = true;

			if(resetPassword) {
				$state.transitionTo('edit-user', {id: user.id});
			} else {
				redirect();
			}
		}

		return {
			clearCredentials: clearCredentials,
			redirect: redirect,
			setLoginAuthDetails: setLoginAuthDetails
		}


	}
})();
