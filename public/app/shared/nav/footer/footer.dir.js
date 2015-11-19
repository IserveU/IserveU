(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.directive('footer', footer);

	function footer($compile) {

		function linkMethod(scope, element, attrs) {
			scope.$watch('currentState', function() {
				angular
					.element(document.getElementById('sidebar-inner'))
					.empty()
					.append($compile("<div class='" + attrs.sidebar + "-sidebar'" + attrs.sidebar + "-sidebar></div>")(scope));
			});
		}

		function controllerMethod(motion, $scope, $location, $state, $rootScope) {
        
  		}	
		
		return {
			restrict: 'E',
			link: linkMethod,
			controller: controllerMethod
		}

	}

}());