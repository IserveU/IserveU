(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('loginModalController', loginModalController);

	function loginModalController($scope, auth, $rootScope) {

		var vm = this;

		vm.cancel = $scope.$dismiss;

		vm.showLoginError = false;

		vm.submit = function(email, password) {

			var credentials = {email:email, password:password};

			auth.login(credentials).success(function(user, status) {
				console.log(user,status);
				if(status === 200 && user.hasOwnProperty('first_name')) {
					$scope.$close(user);
					var user_info = {first_name:user.first_name,last_name:user.last_name};

					$rootScope.currentUser = user;
					$rootScope.userIsLoggedIn = true;
					
				}
				else {
					vm.showLoginError = true;
				}				
			});
		}
	}
})();