(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.directive('sidebar', sidebar);

	function sidebar() {
		
		var directive = {
			link: linkMethod,
			templateUrl: 'app/shared/sidebar/sidebar.tpl.html',
			restrict: 'E',
			controller: controllerMethod,
		}

		return directive;
	}

	function linkMethod(scope, element, attributes){

	}

	function controllerMethod(motion, $scope, $location, $state, $rootScope){
        
        var vm = this;

        vm.motions;
        
        $scope.sidebar = {
			motions: null
		}
 
 		function getMotions(){
        	motion.getMotions().then(function(results) {	        		
				$scope.sidebar.motions = results;
			}, function(error) {
				console.log(error);
			});
        }

		//getMotions();


		function cycleVotes(){
			console.log("to do");
		}

    }

}());