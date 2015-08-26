(function() {

	'use strict';

	angular
		.module('iserveu')
		.service('resetPasswordService', resetPasswordService);


	function resetPasswordService($stateParams, $state, $mdToast, auth, afterauth, user, $rootScope) {

		$rootScope.notifyUser = {
			passwordreset: false
		};

		if($stateParams.resetpassword){
			auth.getNoPassword($stateParams.resetpassword).then(function(data) {
				afterauth.setLoginAuthDetails(data, data.data.token);
				$rootScope.notifyUser.passwordreset = true;
			}, function(error) {
				console.log(error);
				if(error.status === 404){
					console.log('invalid token');
				}
				if(error.status === 403){
					console.log('no token provided');
				}
			});
		}
	}
}());