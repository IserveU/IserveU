(function() {
	
	angular
		.module('iserveu')
		.directive('motionTiles', motionTiles);

	function motionTiles() {

		function motionTilesController($scope, $mdMedia) {
			
			$scope.direction = $mdMedia('gt-sm') ? 'left' : '';

		}

		return {
			controller: ['$scope', '$mdMedia', motionTilesController],
			controllerAs: 'motionTiles',
			templateUrl: 'app/components/motionTiles/motionTiles.tpl.html'
		}


	}

})();