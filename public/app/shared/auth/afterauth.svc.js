(function() {

	'use strict';

	angular
		.module('iserveu')
		.service('afterauth', afterauth);


	function afterauth($stateParams, $state, $mdToast, auth, user, $rootScope) {

		var vm = this;

		vm.setLoginAuthDetails = function(data, token){
			if(token){localStorage.setItem('satellizer_token', JSON.stringify(token));}
			localStorage.setItem('permissions', JSON.stringify(data.data.user.permissions));
			localStorage.setItem('user', JSON.stringify(data.data.user));
			$rootScope.userIsLoggedIn = true;
			$rootScope.authenticatedUser = data.data.user;
			if($rootScope.redirectUrlName){
				$state.go($rootScope.redirectUrlName, {"id": $rootScope.redirectUrlID});
			}
			else{
				$state.go('home');
			}
		}

	}
})();
