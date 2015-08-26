(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('notificationController', notificationController);

	function notificationController(notificationService) {
		
		var vm = this;

		vm.newpassword;

		vm.resetPassword = notificationService.reset;
	}


}());	
