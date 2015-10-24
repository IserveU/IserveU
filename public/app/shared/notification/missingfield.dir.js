(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.directive('missingFields', missingFields);

	function missingFields() {

		function controllerMethod($rootScope, user) {

			var vm = this;
			vm.closeNotificationBox = closeNotificationBox;
			vm.fill_in_fields = false;

			function getUserFields(){
				user.getUser(
					$rootScope.authenticatedUser.id
				).then(function(results){
					getEmptyFields(results);
				})
			}

			function getEmptyFields(fields) {

				angular.forEach(fields, function(value,key) {

					if( key == 'ethnic_origin_id' && value == null ){
						vm.fill_in_fields = true;
					}
					if( key == 'date_of_birth' && value == null){
						vm.fill_in_fields = true;
					}  
					if( key == 'street_name' && value == null) {
						vm.fill_in_fields = true;
					}

				})

			}

			function closeNotificationBox(){
				vm.fill_in_fields = false;
			}

			getUserFields();

  		}	

		return {
			controller: controllerMethod,
			controllerAs: 'ctrl',
			bindToController: true,
		    templateUrl: 'app/shared/notification/missingfields.tpl.html'
		}
	}
}());