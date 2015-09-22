(function() {

	angular
		.module('iserveu')
		.service('FigureService', FigureService);

	function FigureService(figure, $state, $stateParams) {
		
		var vm = this;

		vm.getFigures = getFigures;
		vm.figures;
		vm.figure;

		vm.uploadFile = function(file, motion_id) {
		    figure.saveFigure(file, motion_id).then(function(result) {
		    	getFigures(motion_id);
		    }, function(error) {
		    	console.log(error);
		    });
		}

		function getFigures(motion_id){
			return figure.getFigures(motion_id).then(function(result) {
				return result;
			}, function(error) {
				console.log(error);
			});
		}

		vm.getFigure = function(motion_id, figure_id){
			figure.getFigure(motion_id, figure_id).then(function(result) {
				console.log(result);
				vm.figure = result;
			}, function(error) {
				console.log(error);
			});
		}

		vm.deleteFigure = function(motion_id, figure_id){
			figure.deleteFigure(motion_id, figure_id).then(function(result) {
				console.log(result);
			}, function(error) {
				console.log(error);
			});
		}

	}
}());