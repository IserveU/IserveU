(function() {

	'use strict';

	angular
		.module('iserveu')
		.service('resetPasswordService', resetPasswordService);


	function resetPasswordService($stateParams, $state, ToastMessage, auth, afterauth, user, $timeout, $rootScope, $mdDialog) {

		var vm = this;

		vm.check = function(){
			
			if($state.current.name == 'login.resetpassword'){

				if($rootScope.userIsLoggedIn === true){
					auth.logout();
					localStorage.clear();
					$rootScope.userIsLoggedIn = false;
					postToken();
				}
				else{
					postToken();
				}

			
			}
		}

		function postToken(){

			auth.getNoPassword($stateParams.resetpassword).then(function(data) {
					
					afterauth.setLoginAuthDetails(data, data.data.token);

				}, function(error) {
					if(error.status === 404){
						localStorage.clear();
						$rootScope.userIsLoggedIn = false;
						$state.go('login');
						ToastMessage.simple("Sorry! Your lost password token has expired.");
					}
					if(error.status === 403){
						console.log('no token provided');
					}
			});

		}	

	}
}());