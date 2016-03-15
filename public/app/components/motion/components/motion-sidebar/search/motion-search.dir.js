(function() {
	
	angular
		.module('iserveu')
		.directive('motionSearch', motionSearch);

     /** @ngInject */
	function motionSearch(motionSearchFactory) {

		function motionSearchController($scope) {
			
			$scope.search = motionSearchFactory;

		}

		function motionSearchLink(scope, el, attrs) {

			// TODO: pseudocode
			// 
			// figure out how exactly to detect when you need a 'closed'
			// version and then link that isntead of the non-closed tpl
			//

		}


		return {
			controller: motionSearchController,
			templateUrl: 'app/components/motion/components/motion-sidebar/search/motion-search.tpl.html'
		}


	}

})();