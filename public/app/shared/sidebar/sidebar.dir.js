(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.directive('sidebar', sidebar);

	function sidebar($compile) {

		function linkMethod(scope, element, attrs) {
			scope.$watch('currentState', function() {
				angular
					.element(document.getElementById('sidebar-inner'))
					.empty()
					.append($compile("<div class='new-sidebar'" + attrs.sidebar + "-sidebar></div>")(scope));
			});
		}

		function controllerMethod(motion, $scope, $location, $state, $rootScope) {
        
      var vm = this;
      vm.motions;
        
      $scope.sidebar = {
				motions: null
			}
 
 			function getMotions() {
      	motion.getMotions().then(function(results) {	        		
					$scope.sidebar.motions = results;
				}, function(error) {
					console.log(error);
				});    
  		}
  		
  	}	
		
		return {
			restrict: 'E',
			link: linkMethod,
			controller: controllerMethod
		}

	}

}());