(function() {
	
	angular
		.module('iserveu')
		.directive('editMotionFiles', editMotionFiles);

	function editMotionFiles(editMotionFilesFactory) {

		function editMotionFilesController($scope) {

			$scope.editFile = editMotionFilesFactory;

		}


		return {
			controller: editMotionFilesController,
			templateUrl: 'app/components/motion/components/motion-files/edit-motion-files.tpl.html'
		}


	}

})();