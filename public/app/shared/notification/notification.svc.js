(function() {

	'use strict';

	angular
		.module('iserveu')
		.service('notificationService', notificationService);


	function notificationService($stateParams, $state, $mdToast, auth, user, $rootScope) {

		var vm = this;

		vm.reset = function(newpassword) {
			var data = {
				id: JSON.parse(localStorage.getItem('user')).id,
				password: newpassword
			}
			user.updateUser(data).then(function(result){
				$mdToast.show(
                  $mdToast.simple()
                    .content("Thank you for resetting your password!")
                    .position('bottom right')
                    .hideDelay(3000)
                );
                $rootScope.notifyUser.passwordreset = false;
			}, function(error){
				console.log(error);
			});
		}

	}


}());