(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('afterauth', afterauth);


	function afterauth($stateParams, $state, $mdToast, $rootScope, auth, user, SetPermissionsService) {

		 function setLoginAuthDetails (data, token){
			if(token){  localStorage.setItem('satellizer_token', JSON.stringify(token));  }
			SetPermissionsService.set(JSON.stringify(data.data.user.permissions));
			localStorage.setItem('user', JSON.stringify(data.data.user));
			$rootScope.authenticatedUser = data.data.user;
			redirect();
		}

		function redirect(){

			$rootScope.userIsLoggedIn = true;

			return $rootScope.redirectUrlName ? 
				$state.go($rootScope.redirectUrlName, {"id": $rootScope.redirectUrlID}) :
				$state.go('home');
		}

		function clearCredentials(){
			localStorage.clear();
			$rootScope.authenticatedUser = null;
			$rootScope.userIsLoggedIn = false;
			$state.go('login', {});		
		}

		return {
			setLoginAuthDetails: setLoginAuthDetails,
			redirect: redirect,
			clearCredentials: clearCredentials
		}


	}
})();
