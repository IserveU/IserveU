(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.directive('formatAddress', formatAddress);

	function formatAddress($filter) {

		return {
			require: "ngModel",
			link: function(scope, element, attrs, ngModelController) {

      			ngModelController.$formatters.push(function(data) {
      				if(!data.street_name){
      					return "Address not set";
      				}
      				else
      				if(!data.unit_number){
      					return data.street_number + ' ' + data.street_name.charAt(0).toUpperCase() + data.street_name.substr(1).toLowerCase();
      				}
      				else{
						return data.unit_number + ' ' + data.street_number + ' ' + data.street_name.charAt(0).toUpperCase() + data.street_name.substr(1).toLowerCase();
      				}
			    });
			}
		}
	}

}());

