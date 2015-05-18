(function() {


	angular.module( 'iserveu.sidebar', [])

	.directive('sidebar', function() {
	    var templatePath,
	        linkMethod,
	        controllerMethod;

	    templatePath = 'app/shared/sidebar/sidebar.tpl.html';

	    linkMethod = function(scope, element, attributes) {
	    };

	    controllerMethod = function(motion, event, $scope, $location, $state) {
	      
	        var vm = this;
	        vm.motions;
	        vm.events;

	        $scope.sidebar = {
				events: null,
				motions: null
			}
	 
	 		function getMotions(){
	        	motion.getMotions().then(function(results) {
	        		console.log(results);
					$scope.sidebar.motions = results;
				}, function(error) {
					console.log(error);
				});
	        }

	        function getEvents(){

				event.getEvents().then(function(results) {
				

					$scope.sidebar.events = results;
					

				}, function(error) {
					console.log(error);
				});
			}


			getMotions();
			getEvents();

	        	       
	    };

	    return {
	        restrict: 'E',
	        templateUrl: templatePath,
	        link: linkMethod,
	        controller: controllerMethod
	    };
	});

})();