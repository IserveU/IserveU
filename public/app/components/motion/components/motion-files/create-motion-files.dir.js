(function() {
	
	angular
		.module('iserveu')
		.directive('createMotionFiles', createMotionFiles);

	function createMotionFiles(motionFilesFactory, editMotionFilesFactory) {

		function createMotionFilesController($scope) {
			
			$scope.motionFile = motionFilesFactory;
			
			$scope.editFile = {

				show: [],
				saving: [],
				success: [],
				edit: function(i) {
					for (var j in this.show)
						this.show[j][i] = false;
					this.show[i] = !this.show[i];
				},
				saveTitle: function() {

				},
				pressEnter: function(ev, title, msg) {

				}

			};

		}


		return {
			controller: createMotionFilesController,
			templateUrl: 'app/components/motion/components/motion-files/create-motion-files.tpl.html'
		}


	}

})();