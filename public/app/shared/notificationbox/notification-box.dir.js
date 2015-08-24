(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.directive('notificationBox', notificationBox);

	function notificationBox($compile) {


		function linkMethod(scope, element, attrs) {
		}

		function controllerMethod(motion, $scope, $location, $state, $rootScope) {
        
 	 	}	
		
		return {
			restrict: 'E',
			link: linkMethod,
			controller: controllerMethod,
			templateUrl: 'app/shared/notificationbox/notification-box.tpl.html',
		}

	}

}());