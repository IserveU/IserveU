(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('PreviewImageController', PreviewImageController);

	function PreviewImageController($rootScope, $state, $scope, ToastMessage, $stateParams, backgroundimage) {

		var vm = this;

		vm.file = "uploads/background_images/";

		function getPreviewFile(id){
			backgroundimage.getBackgroundImage(id).then(function(result){
				vm.file = vm.file + result.data.file;
			}, function(error){
				console.log(error);
			})
		}

		getPreviewFile($stateParams.id);

	}


}());	
