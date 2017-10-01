(function() {

	'use strict';

	angular
		.module('app.login')
		.factory('ResetPassword', [
			'$rootScope',
			'$state',
			'$stateParams',
      'Auth',
			'AuthResource',
      'Redirect',
			'ToastMessage',
		ResetPasswordFactory]);

	function ResetPasswordFactory($rootScope, $state, $stateParams, Auth, AuthResource, Redirect, ToastMessage) {

    var service = {
      checkToken: checkToken
    };

    return service;

/** ============== Functions ======================= */

		function checkToken() {
			if ($state.current.name === 'reset-password.token') {

				if ($rootScope.userIsLoggedIn) {
					Auth.logout();
				}

				return postToken($stateParams.token);
			}
		};

		function postToken(token) {
			$rootScope.redirectUrlName = 'reset-password';
      AuthResource.rememberToken(token).then(function(response) {
          // TODO: Redirect.stateName = 'reset-password';
          Auth.handleAuthentication(response);
				}, function(error) {
					if (error.status === 404) {
            Redirect.redirect();
						ToastMessage.simple("Sorry! Your lost password token has expired.");
					}
			});

		}

    /** deprecated */
		function successHandler(res) {
			// var user = res.user || res.data || res;

			// $rootScope.userIsLoggedIn    = true;
			// $rootScope.authenticatedUser = user;
			// $rootScope.authenticatedUser.permissions = Utils
			// 	.transformObjectToArray($rootScope.authenticatedUser.permissions);

			// localStorage.setItem( 'api_token', user.api_token );
			// localStorage.setItem( 'user', JSON.stringify(user) );
			// MotionIndex.clear();
		}



	}
}());
