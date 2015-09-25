(function() {

	angular
		.module('iserveu')
		.service('MotionFileService', MotionFileService);

	function MotionFileService($state, $stateParams, motionfile, ToastMessage) {
		
		var vm = this;

		vm.getFigures = getFigures;
		vm.motionfiles;
		vm.motionfile;

		vm.uploadFile = function(file, motion_id) {
		    motionfile.saveFigure(file, motion_id).then(function(result) {
		    	getFigures(motion_id);
		    }, function(error) {
		    	console.log(error);
		    });
		}

		function getFigures(motion_id){
			return motionfile.getFigures(motion_id).then(function(result) {
				return result;
			}, function(error) {
				console.log(error);
			});
		}

		vm.getFigure = function(motion_id, figure_id){
			motionfile.getFigure(motion_id, figure_id).then(function(result) {
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