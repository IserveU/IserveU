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
					return new Date(data);
				})

      			ngModelController.$formatters.push(function(data) {
					return new Date(data);
			    });
			}
		}
	}

}());

