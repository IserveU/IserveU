(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.directive('formatClosing', ['$filter', formatClosing]);


  	 /** @ngInject */
	function formatClosing($filter) {

		return {
			require: "ngModel",
			link: function(scope, element, attrs, ngModelController) {

				ngModelController.$parsers.push(function(data) {
					return $filter('date')(data, "yyyy-MM-dd HH:mm:ss");
				})

      			ngModelController.$formatters.push(function(data) {
      				
      				var date = data.carbon ? 
	      				new Date(data.carbon.date) : new Date();
      				
      				return date;
			    });
			}
		}
	}

}());

