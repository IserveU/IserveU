(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('userManager', userManager);

	function userManager(user) {


		function userController() {

			var vm = this;

			vm.users = {};

			user.getIndex().then(function(r) {
				vm.users = r.data;
			}, function(e) {
				console.log(e);
			})

		};


		return {
			controller: userController,
			controllerAs: 'user',
			templateUrl: 'app/components/admin/partials/user-manager/user-manager.tpl.html',
		}


	}


})();