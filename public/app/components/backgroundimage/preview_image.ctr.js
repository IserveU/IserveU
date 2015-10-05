(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('PreviewImageController', PreviewImageController);

	function PreviewImageController($rootScope, $state, $scope, ToastMessage, $stateParams, backgroundimage) {

		var vm = this;

		vm.filename = "uploads/background_images/";

		function getPreviewFile(id){
			backgroundimage.getBackgroundImage(id).then(function(result){
				vm.filename = vm.filename + result.data.filename;
			}, function(error){
				console.log(error);
			})
		}

		getPreviewFile($stateParams.id);

	}


}());	
