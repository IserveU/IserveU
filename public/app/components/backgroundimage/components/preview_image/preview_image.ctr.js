(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('PreviewImageController', PreviewImageController);

	function PreviewImageController($rootScope, $stateParams, $state, ToastMessage, backgroundimage, UserbarService) {

		var vm = this;

		vm.filename = "uploads/background_images/";
		vm.updateBackgroundImage = updateBackgroundImage;
		vm.deleteBackgroundImage = deleteBackgroundImage;
		vm.updating = false;

		backgroundimage.getBackgroundImage($stateParams.id).then(function(result){
			vm.fileinfo = result.data;
			vm.filename = vm.filename + result.data.filename;
			UserbarService.title = result.data.filename;
		}, function(error){
			console.log(error);
		})

		function updateBackgroundImage(){
			var data = {
				id: vm.fileinfo.id,
				credited: vm.fileinfo.credited,
				active: vm.fileinfo.active,
				url: vm.fileinfo.url,
				display_date: vm.fileinfo.display_date
			}

			backgroundimage.updateBackgroundImage(data).then(function(result){
				ToastMessage.simple("Successfully updated.");
				$rootScope.$emit('backgroundImageUpdated');
				vm.updating = false;
			}, function(error){
				vm.updating = false;
				ToastMessage.report_error(error);
			})
		}

		function deleteBackgroundImage(){
			backgroundimage.deleteBackgroundImage(vm.fileinfo.id).then(function(result){
				ToastMessage.simple("Image deleted.");
				$state.go('backgroundimage');
				$rootScope.$emit('backgroundImageUpdated');
			}, function(error) {
				ToastMessage.report_error(error);
			})
		}


	}


}());	
