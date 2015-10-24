(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.directive('missingFields', missingFields);

	function missingFields() {

		function controllerMethod($mdDialog, $scope) {

			var vm = this;

			vm.fill_in_fields		= false;
			vm.closeNotificationBox = closeNotificationBox;

			function getUserFields(){
				var fields = JSON.parse(localStorage.getItem('user'));
				getEmptyFields(fields);
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

	  	function linkMethod(scope, element, attrs){
	  		attrs.$observe('needsTo', function(value){
				if(value == 'false'){
					element.remove('missingFields');
				}
			})
	  	}

		return {
			controller: controllerMethod,
			link: linkMethod,
			controllerAs: 'ctrl',
			bindToController: true,
		    templateUrl: 'app/shared/notification/missingfields.tpl.html'
		}
	}
}());