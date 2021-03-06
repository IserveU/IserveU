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
			'utils',
			'motionIndex',
      'localStorageManager',
      'redirectService',
		resetPasswordService]);

	function resetPasswordService($rootScope, $state, $stateParams,
																authResource, loginService,
																ToastMessage, utils, motionIndex, localStorageManager,
                                redirectService) {

		var checkToken = function(){
			if ($state.current.name === 'reset-password.token') {

				if ($rootScope.userIsLoggedIn === true) {
					authResource.logout();
					localStorageManager.clear();
					$rootScope.userIsLoggedIn = false;
				}
				postToken($stateParams.token);
			}
		};

		function postToken(token){
			authResource.rememberToken(token).then(function(results) {
					$rootScope.redirectUrlName = 'reset-password';
					successHandler(results.data);
				}, function(error) {
					if (error.status === 404) {
            $rootScope.redirectUrlName = 'reset-password';
            redirectService.redirect('home');
						//loginService.clearCredentials(true);
						ToastMessage.simple("Sorry! Your lost password token has expired.");
					}
			});

		}

		function successHandler(res) {
			var user = res.user || res.data || res;

			$rootScope.userIsLoggedIn    = true;
			$rootScope.authenticatedUser = user;
			$rootScope.authenticatedUser.permissions = utils
				.transformObjectToArray($rootScope.authenticatedUser.permissions);

			localStorage.setItem( 'api_token', user.api_token );
			localStorage.setItem( 'user', JSON.stringify(user) );
			motionIndex.clear();
		}

		return {
			check: checkToken
		}

	}
}());
