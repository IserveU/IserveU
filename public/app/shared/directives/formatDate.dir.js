(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.directive('formatDate', formatDate);

	function formatDate($filter) {

		return {
			require: "ngModel",
			link: function(scope, element, attrs, ngModelController) {


				ngModelController.$parsers.push(function(data) {
					return $filter('date')(data, "yyyy-MM-dd HH:mm:ss");
				})

      			ngModelController.$formatters.push(function(data) {
      				if(data === "0000-00-00" || data === null ) {
      					// TODO: make this more flexible to reuse this directive not just for birthdays
      					return "Enter your birthday";
      				}
      				else {
      					var transformedDate = new $filter('date')(data, 'MMMM d, yyyy');
						return transformedDate;
      				}
			    });
			}
		}
	}

}());

