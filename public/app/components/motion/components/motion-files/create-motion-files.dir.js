(function() {
	
	angular
		.module('iserveu')
		.directive('createMotionFiles', createMotionFiles);

	function createMotionFiles(motionFilesFactory) {

		function createMotionFilesController($scope) {
			
			$scope.motionFile = motionFilesFactory;

		}


		return {
			controller: createMotionFilesController,
			templateUrl: 'app/components/motion/components/motion-files/create-motion-files.tpl.html'
		}


	}

})();