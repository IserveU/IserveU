(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.directive('convertClosingDate', convertClosingDate);

	function convertClosingDate($filter) {

		return {
			require: "ngModel",
			link: function(scope, element, attrs, ngModelController) {

				ngModelController.$parsers.push(function(data) {
					return $filter('date')(data, "yyyy-MM-dd HH:mm:ss");
				})

      			ngModelController.$formatters.push(function(data) {
      				data.setDate(data.getDate() + 7);
      				return data;
			    });
			}
		}
	}

}());

