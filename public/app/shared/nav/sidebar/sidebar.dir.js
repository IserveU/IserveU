(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.directive('sidebar', ['$compile', sidebar]);

  	 /** @ngInject */
	function sidebar($compile) {

		function linkMethod(scope, element, attrs) {

			scope.$watch('currentState', function() {
				angular
					.element(document.getElementById('sidebar-inner'))
					.empty()
					.append($compile("<div class='" + attrs.sidebar + "-sidebar'" + attrs.sidebar + "-sidebar></div>")(scope));
			});
		}

		
		return {
			restrict: 'E',
			link: linkMethod,
		}

	}

}());

