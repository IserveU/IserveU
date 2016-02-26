(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('CreateUserController', CreateUserController);

  	 /** @ngInject */
	function CreateUserController($rootScope, $scope, $state, user, ToastMessage){
		var vm = this;

		vm.first_name;
		vm.middle_name;
		vm.last_name;
		vm.email;

		vm.creating = false;

		vm.createNewUser = function(){

			vm.creating = true;

			var data = {
				first_name: vm.first_name,
				middle_name: vm.middle_name,
				last_name: vm.last_name,
				email: vm.email,
				password: 'password'
			}

			user.storeUser(data).then(function(results) {
				vm.creating = false;
				vm.first_name = '';
				vm.middle_name = '';
				vm.last_name = '';
				vm.email = '';
				$scope.newUserAdminForm.$setPristine();
				ToastMessage.simple("User created successfully!");
			})
		}

	}

})();