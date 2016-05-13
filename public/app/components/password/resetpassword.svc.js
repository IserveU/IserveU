(function() {

	'use strict';

	angular
		.module('iserveu')
		.service('resetPasswordService', [
			'$rootScope', '$state', '$stateParams', 
			'auth', 'afterauth', 'ToastMessage',
			resetPasswordService]);

  	 /** @ngInject */
	function resetPasswordService($rootScope, $state, $stateParams, auth, afterauth, ToastMessage) {

		this.check = function(){
			
			if($state.current.name == 'login.resetpassword')

				if($rootScope.userIsLoggedIn === true){
				
					auth.logout();
					localStorage.clear();
					$rootScope.userIsLoggedIn = false;
					postToken();
				
				} else 
					postToken();	
		};

		function postToken(){

			auth.getNoPassword($stateParams.token).then(function(data) {
					
					var r = data.data;

					afterauth.setLoginAuthDetails(r.user, r.token, true);

				}, function(error) {

					if(error.status === 404){
						localStorage.clear();
						$rootScope.userIsLoggedIn = false;
						$state.go('login', {}, {reload:true});
						ToastMessage.simple("Sorry! Your lost password token has expired.");
					}

					if(error.status === 403)
						console.log('no token provided');
			});

		}	

	}
}());