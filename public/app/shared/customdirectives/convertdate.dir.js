(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.directive('convertDate', convertDate);

	function convertDate() {

		return {
			require: "ngModel",
			link: function(scope, element, attrs, ngModelController) {
      			ngModelController.$formatters.push(function(data) {
	       		 	var transformedDate = new Date(data);
			        return transformedDate; //converted
			    });
			}
		}
	}

}());