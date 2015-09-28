(function() {

	angular
		.module('iserveu')
		.service('MotionFileService', MotionFileService);

	function MotionFileService($state, $stateParams, motionfile, ToastMessage) {
		
		var vm = this;

		vm.getMotionFiles = getMotionFiles;
		vm.motionfile;

		vm.uploadFile = function(file, motion_id) {
		    motionfile.uploadMotionFile(file, motion_id).then(function(result) {
		    	getMotionFiles(motion_id);
		    }, function(error) {
		    	console.log(error);
		    });
		}

		function getMotionFiles(motion_id){
			return motionfile.getMotionFiles(motion_id).then(function(result) {
				return result;
			}, function(error) {
				console.log(error);
			});
		}

		vm.getMotionFile = function(motion_id, figure_id){
			motionfile.getMotionFile(motion_id, figure_id).then(function(result) {
				vm.motionfile = result;
			}, function(error) {
				console.log(error);
			});
		}

		vm.deleteFigure = function(motion_id, figure_id){
			motionfile.deleteFigure(motion_id, figure_id).then(function(result) {
				
			}, function(error) {
				ToastMessage.report_error(error);
			});
		}

	}
}());