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
					var date = new Date(data);
					return $filter('date')(data, "yyyy-MM-dd HH:mm:ss");
				})

      			ngModelController.$formatters.push(function(data) {
					var oneWeekDate = new Date();   //sets for next week
      				oneWeekDate.setDate(oneWeekDate.getDate() + 7);
      				return oneWeekDate;
			    });
			}
		}
	}

}());

