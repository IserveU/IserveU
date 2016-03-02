(function() {
	
	angular
		.module('iserveu')
		.directive('displayMotionFile', displayMotionFile);

	function displayMotionFile($stateParams, motionfile) {

		function displayMotionFileController() {
			
			// do something

		}


		return {
			controller: displayMotionFileController,
			templateUrl: 'app/components/motion/components/motion-files/display-motion-files.tpl.html'
		}

	}

})();