(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.directive('convertDate', convertDate);

	function convertDate($filter) {

		return {
			require: "ngModel",
			link: function(scope, element, attrs, ngModelController) {

				ngModelController.$parsers.push(function(data) {
					return new Date(data);
				})


      			ngModelController.$formatters.push(function(data) {
      				if(data == "0000-00-00") {
      					var transformedDate = "Birthday not set";
      				}
      				else {
	       			 	return $filter('date')(new Date(data), "MMMM dd, yyyy");
      				}
			        return transformedDate; //converted
			    });
			}
		}
	}

}());

