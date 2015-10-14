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
					return $filter('date')(data, "yyyy-MM-dd HH:mm:ss");
				})

      			ngModelController.$formatters.push(function(data) {
      				if(data == "0000-00-00") {
      					return;
      				}
      				else if(data == null){
      					return;
      				}
      				else {
      					var transformedDate = new Date(data);
      					transformedDate.setTime(transformedDate.getTime()+transformedDate.getTimezoneOffset()*60000);
						return transformedDate;
      				}
			    });
			}
		}
	}

}());

