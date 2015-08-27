(function() {

	angular
		.module('iserveu')
		.controller('FigureController', FigureController);

	function FigureController(figure, $scope) {
		
		var vm = this;

		vm.thisFile;

		$scope.chosenImage = function(files){
			vm.thisFile = files;
		}

		vm.uploadFile = function(file) {
			var formData = new FormData();
		    //Take the first selected file
		    formData.append("file", vm.thisFile[0]);

		    figure.saveFigure(1).then(function(result) {
		    	console.log(result);
		    }, function(error) {
		    	console.log(error);
		    });
		}






	}
})();