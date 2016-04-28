(function() {
	
	angular
		.module('iserveu')
		.service('logoutService', [
			'$rootScope', '$state', 'auth', 'afterauth', 'commentObj', 'motionObj', 'voteObj'
			,logoutService]);

	function logoutService($rootScope, $state, auth, afterauth, commentObj, motionObj, voteObj) {

		return function() {
			
			$rootScope.pageLoading = true;
			$state.transitionTo('login');		

			motionObj.clear();
			commentObj.clear();
			voteObj.clear();

			auth.logout().then(function() {
				afterauth.clearCredentials();
			});
		};
	}

})();