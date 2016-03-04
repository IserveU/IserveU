(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.directive('formatDate', formatDate);

  	 /** @ngInject */
	function formatDate($filter) {

		return {
			require: "ngModel",
			link: function(scope, element, attrs, ngModelController) {

				ngModelController.$parsers.push(function(data) {
					return $filter('date')(data, "yyyy-MM-dd HH:mm:ss Z");
				});

      			ngModelController.$formatters.push(function(data) {
					return new Date(data);
			    });
			}
		}
	}

}());
