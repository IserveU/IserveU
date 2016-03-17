(function() {
	
	angular
		.module('iserveu')
		.service('logoutService', logoutService);

	function logoutService($state, auth, afterauth, commentObj, motionObj, voteObj) {

		return function() {
		
			$state.go('login', {});		

			motionObj.clear();
			commentObj.clear();
			voteObj.clear();

			auth.logout().then(function() {
				afterauth.clearCredentials();
			});
		};


	}

})();