(function() {

	'use strict';

	angular
		.module('iserveu')
		.service('resetPasswordService', resetPasswordService);


	function resetPasswordService($stateParams, $state, ToastMessage, auth, afterauth, user, $timeout, $rootScope, $mdDialog) {

		var vm = this;

		if($stateParams.resetpassword){
			auth.getNoPassword($stateParams.resetpassword).then(function(data) {
				afterauth.setLoginAuthDetails(data, data.data.token);
				$timeout(function() {
					$rootScope.$emit('resetPasswordDialog');
				}, 500);
			}, function(error) {
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