(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('NotificationController', NotificationController);

	function NotificationController(notificationService, user, $mdDialog, $timeout, ethnic_origin, $scope, $rootScope) {
		
		var vm = this;

		vm.newpassword;

		vm.resetPassword = notificationService.reset;

		vm.fields = notificationService.fields;

	}


}());	
