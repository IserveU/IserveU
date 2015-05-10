(function() {


	angular.module( 'iserveu.sidebar', [])

	.directive('sidebar', function() {
	    var templatePath,
	        linkMethod,
	        controllerMethod;

	    templatePath = 'app/shared/sidebar/sidebar.tpl.html';

	    linkMethod = function(scope, element, attributes) {
	    };

	    controllerMethod = function(motion, $scope, $location, $state) {
	        $scope.$state = $state;
	        var navItems = [];
	        var motions = [];

	 
        	motion.getMotions().then(function(results) {
				motions = results;

				$scope.sidebar = {
					motions: motions /*This is probably retarded, the data isn't bound */
				}

			}, function(error) {
				console.log(error);
			});
		


	        $scope.sidebar = {
	            navItems: navItems,
	            motions: motions
	        };
	        	       
	    };

	    return {
	        restrict: 'E',
	        templateUrl: templatePath,
	        link: linkMethod,
	        controller: controllerMethod
	    };
	});

})();