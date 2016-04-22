(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('afterauth', [
'$stateParams', '$state', '$rootScope', 'auth', 'user',
			afterauth]);

  	 /** @ngInject */
	function afterauth($stateParams, $state, $rootScope, auth, user) {

		 function setLoginAuthDetails (user, token, resetPassword){
			if(token)
				localStorage.setItem( 'satellizer_token', JSON.stringify( token ) );

			localStorage.setItem( 'user', JSON.stringify(user) );
			$rootScope.authenticatedUser = user;

			if(resetPassword){
				$rootScope.userIsLoggedIn = true;
				$state.transitionTo('edit-user', {id: user.id});
			}
			else
				redirect();
		}

		function redirect(){

			$rootScope.userIsLoggedIn = true;

			return $rootScope.redirectUrlName 
				   ? $state.transitionTo($rootScope.redirectUrlName, {"id": $rootScope.redirectUrlID}) 
				   : $state.transitionTo('home');
		}

		function clearCredentials(){
			localStorage.clear();
			$rootScope.authenticatedUser = null;
			$rootScope.userIsLoggedIn = false;
		}

		return {
			setLoginAuthDetails: setLoginAuthDetails,
			redirect: redirect,
			clearCredentials: clearCredentials
		}


	}
})();
