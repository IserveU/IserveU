(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('loginController', login);

	function login($scope, $http, $rootScope, $location, auth, $modal, loginModal, $state) {

		var vm = this;

		vm.loginError = false;

		vm.logUserIn = function(email, password) {

			var credentials = { email: email, password: password };

			auth.login(credentials).success(function(data) {
				vm.loginError = false;
				$rootScope.userIsLoggedIn = true;
			}).error(function(data) {
				console.log('There was an error logging in');
			});			
		}

		vm.logUserOut = function() {

			auth.logout().success(function() {
				$rootScope.userIsLoggedIn = false;
				$state.go('home');
				$rootScope.currentUser = undefined;

			});			
		}

		vm.openModal = function() {
			$modal.open({
				templateUrl: 'app/components/login/loginModalTemplate.html'
			});
		}
		
	}

})();