(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('BackgroundImageController', BackgroundImageController);

	function BackgroundImageController($scope, $rootScope, $state, $mdToast, UserbarService, backgroundimage) {	

		UserbarService.setTitle("Background Images");

		var vm = this;
		
		vm.isactive = 0;
		$scope.themename = '';
		$scope.backgroundcredits = '';

		vm.backgroundimages;


		var settings = JSON.parse(localStorage.getItem('settings'));

		if(settings) {
			$scope.themename = settings.themename;
			$scope.backgroundcredits = settings.background_image;
		}


		vm.uploading = false;
		vm.onSuccess = false;
		vm.showError = false;

		$scope.chosenImage = function(files){
			vm.thisFile = files;
		}

		//this is returning a 400 error, must fix
		function backgroundImages(){
			backgroundimage.getBackgroundImages().then(function(result) {
				vm.backgroundimages = result.data;
			}, function(error) {
				console.log(error);
			});
		}

		vm.uploadFile = function(credited, url){
			var formData = new FormData();
		    //Take the first selected file
		    formData.append("file", vm.thisFile[0]);
		    formData.append("credited", credited);
		    if(url && !/^(http):\/\//i.test(url)) {
                    url = 'http://' + url;
            }
		    formData.append("url", url);
		    formData.append('active', vm.isactive);


		    backgroundimage.saveBackgroundImage(formData).then(function(result) {
	            $rootScope.$emit('backgroundImageUploaded', []);
		    	var user = "Your image has been sent in for approval!"
		    	var admin = "Upload successful!"
		    	vm.onSuccess = true;
		    	vm.uploading = false;
			    	$mdToast.show(
	                  $mdToast.simple()
	                    .content(vm.adminbackgroundimages ? admin : user)
	                    .position('bottom right')
	                    .hideDelay(3000)
	                );
	            $state.reload();
		    },function(error){
		    	console.log("error");
		    	vm.uploading = false;
		    	vm.showError = true;
		    });

		}

		$scope.fillInCredits = function(credited, url, active) {
			var data = {
				credited: credited,
				url: url,
				active: 1
			}
			backgroundimage.saveBackgroundImage(data).then(function(result) {
		    	$state.go('home')
		    });
		}

		vm.previewimage = function(image){
			$rootScope.$emit('imagePreview', image);


			$mdToast.show(
	          $mdToast.simple()
	            .content("Make permanent?")
	            .position('bottom right')
	            .hideDelay(3000)
	        );
		}

		vm.uploadcheck = function() {
			vm.uploading = true;
		}

		backgroundImages();


    }

}());