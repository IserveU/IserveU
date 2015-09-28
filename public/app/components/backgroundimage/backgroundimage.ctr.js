(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('BackgroundImageController', BackgroundImageController);

	function BackgroundImageController($rootScope, $state, $scope, $timeout, ToastMessage, UserbarService, SetPermissionsService, backgroundimage) {	

		// UserbarService.setTitle("IserveU");

		var vm = this;

		$timeout(function(){
			vm.background = JSON.parse(localStorage.getItem('settings')).background_image;
		}, 1000);
		
		vm.isactive = 0;
		vm.backgroundimages;
		vm.preview = false;
		vm.ispreviewimage = true;
		vm.uploading = false;
		vm.onSuccess = false;
		vm.showError = false;
		vm.isNotAdmin = true;
		vm.url;
		vm.credited;

		function isAdmin(){
			vm.isNotAdmin = !SetPermissionsService.can('administrate-background_images');
		}

		function backgroundImages(){
			backgroundimage.getBackgroundImages().then(function(result) {
				vm.backgroundimages = result.data;
			}, function(error) {
				console.log(error);
			});
		}

		vm.previewImage = function(image) {
			$scope.image = "uploads/background_images/"+image.file;
		}

		vm.uploadFile = function(){
			var formData = new FormData();
		    formData.append("file", vm.thisFile);
		    formData.append("credited", vm.credited);
		    if(vm.url && !/^(http):\/\//i.test(vm.url)) {vm.url = 'http://' + vm.url;}
		    formData.append("url", vm.url);
		    formData.append('active', vm.isactive);

		    backgroundimage.saveBackgroundImage(formData).then(function(result) {
	            $rootScope.$emit('refreshLocalStorageSettings', []);
		    	var user = "Your image has been sent in for approval!"
		    	var admin = "Upload successful!"
		    	vm.onSuccess = true;
		    	vm.uploading = false;
		    	ToastMessage.double(admin, user, vm.isNotAdmin);
	            $state.reload();
		    },function(error){
		    	vm.uploading = false;
		    	vm.showError = true;
		    });

		}

		vm.upload = function(flow){
			vm.preview = true;
			vm.thisFile = flow.files[0].file;
		}

		backgroundImages();
		isAdmin();


    }

}());