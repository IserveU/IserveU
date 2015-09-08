(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('BackgroundImageController', BackgroundImageController);

	function BackgroundImageController($rootScope, $state, $scope, ToastMessage, UserbarService, SetPermissionsService, backgroundimage) {	

		UserbarService.setTitle("Background Images");

		var vm = this;
		
		vm.isactive = 0;
		vm.backgroundimages;
		vm.preview = false;
		vm.ispreviewimage = true;
		vm.uploading = false;
		vm.onSuccess = false;
		vm.showError = false;
		vm.isNotAdmin = true;

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

		vm.uploadFile = function(credited, url){
			var formData = new FormData();
		    formData.append("file", vm.thisFile);
		    formData.append("credited", credited);
		    if(url && !/^(http):\/\//i.test(url)) {url = 'http://' + url;}
		    formData.append("url", url);
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