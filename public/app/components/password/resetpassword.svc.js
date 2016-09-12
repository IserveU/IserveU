(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('resetPasswordService', [
			'$rootScope',
			'$state',
			'$stateParams', 
			'authResource',
			'loginService',
			'ToastMessage',
		resetPasswordService]);

  	 /** @ngInject */
	function resetPasswordService($rootScope, $state, $stateParams, authResource, loginService, ToastMessage) {

		var checkToken = function(){
			if($state.current.name == 'login.resetpassword') {

				if($rootScope.userIsLoggedIn === true){
					authResource.logout();
					localStorage.clear();
					$rootScope.userIsLoggedIn = false;
				}

				postToken($stateParams.token);
			}
		};

		function postToken(token){
			authResource.rememberToken( token ).then(function(results) {
					loginService.successHandler( results.data );
				}, function(error) {
					if(error.status === 404){
						loginService.clearCredentials(true);
						ToastMessage.simple("Sorry! Your lost password token has expired.");
					}
			});

		}

		return {
			check: checkToken
		}

	}
}());