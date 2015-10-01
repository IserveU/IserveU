(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('ResetPasswordController', ResetPasswordController);

	function ResetPasswordController($rootScope, user, ToastMessage, resetPasswordService){

		var vm = this;

		$rootScope.$on('resetPasswordNotification', function(events){
			vm.notification = true;
		});

		vm.savePassword = function(){

			var data = {
				id: JSON.parse(localStorage.getItem('user')).id,
				password: vm.password
			}

			user.updateUser(data).then(function(){
				vm.notification = false;
				ToastMessage.simple("Thank you for reseting your password.")
			});

		}



	}

})();