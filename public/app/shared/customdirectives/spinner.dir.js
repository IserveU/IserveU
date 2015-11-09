(function() {
	'use strict';

	angular
		.module('iserveu')
		.directive('loadingSpinner', spinner);

	function spinner($compile){

		function linkMethod(scope, element, attrs){

			var oldContent = angular.element(element).clone();
			var elementSelector;

			attrs.$observe('checkIf', function(value) {
				if (value == 'true') {
					elementSelector = angular.element(element);
					
					elementSelector
						.empty()
						.append($compile("<md-icon md-svg-src='/themes/{{::themename}}/loading.svg'></md-icon>")(scope));
				}
				else if (elementSelector){
					elementSelector
						.empty()
						.append($compile(oldContent[0].innerHTML)(scope));
					elementSelector = null;
				}
			});
		}
	
		return {
			link: linkMethod
		}

	}



})();