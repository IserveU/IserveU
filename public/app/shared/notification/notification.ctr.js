(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('NotificationController', NotificationController);

	function NotificationController(notificationService, $scope, $rootScope) {
		
		var vm = this;

		vm.newpassword;

		vm.resetPassword = notificationService.reset;

		vm.fields = notificationService.fields;

		var user_id = notificationService.id;

		// plug in a drop down select box for this later
		vm.address = {
			street_number: '',
			street_name: '',
			postal_code: '',
			city: "Yellowknife",
			territory: "Northwest Territories"
		}

		vm.upload = function(flow, index){
			vm.thisFile = flow.files;
		}

		vm.uploadIdentification = function(){
			angular.forEach(vm.thisFile, function(value, key){
				var formData = new FormData();
				formData.append("file", value.file);
				formData.append("user_id", user_id);
				// api post to upload files
			});
		}

	}


}());	
