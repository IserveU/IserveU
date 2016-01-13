(function() {


	'use strict';

	angular
		.module('iserveu')
		.directive('ethnicOrigin', ethnicOrigin);

	function ethnicOrigin() {

		return {
			require: 'ngModel',
			link: function(scope, el, attrs, ngModelController) {

				ngModelController.$formatters.push(function(data) {
					if(!data) {
						return "Enter your ethnicity";
					}
					else {
						return data;
					}
				});
			}

		}	

	}

})();