(function() {


	'use strict';


	angular
		.module('iserveu')
		.directive('formatDate', formatDate);


	function formatDate($filter) {


		return {
			restrict: 'A',
			require: 'ngModel',
			link: function(scope, el, attrs, ngModelCtrl) {
				ngModelCtrl.$parsers.push(function(data) {

					// return new Date(data);
					// return $filter('date')(data, "yyyy-MM-dd HH:mm:ss Z");
				});

				ngModelCtrl.$formatters.push(function(data) {
					return new Date(data);

					// return $filter('date')(data, "yyyy-MM-dd HH:mm:ss Z");
				});
			}
		}

	}


})();