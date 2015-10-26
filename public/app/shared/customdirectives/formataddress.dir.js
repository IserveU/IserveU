(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.directive('formatAddress', formatAddress);

	function formatAddress($filter) {

		return {
			require: "ngModel",
			link: function(scope, element, attrs, ngModelController) {

				function toTitleCase(str)
					{
					    return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
					}


      			ngModelController.$formatters.push(function(data) {
      				if(!data.street_name){
      					return "Address not set";
      				}
      				else
      				if(!data.unit_number){
      					return data.street_number + ' ' + toTitleCase(data.street_name);
      				}
      				else{
						return data.unit_number + '-' + data.street_number + ' ' + toTitleCase(data.street_name);
      				}
			    });
			}
		}
	}

}());

