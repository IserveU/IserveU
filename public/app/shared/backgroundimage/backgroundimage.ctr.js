(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('BackgroundImageController', BackgroundImageController);

	function BackgroundImageController($scope, $rootScope, $state, $mdToast, UserbarService, backgroundimage) {	

		UserbarService.setTitle("Background Images");

		var vm = this;
		
		vm.isactive = 0;
		vm.adminbackgroundimages = false;
		$scope.themename = '';
		$scope.backgroundcredits = '';
		var settings = JSON.parse(localStorage.getItem('settings'));
		var permissions = JSON.parse(localStorage.getItem('permissions'));
		if(permissions){
			if(permissions.indexOf('administrate-background_images') != -1) {
				vm.adminbackgroundimages = true;
			}
		}
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

		vm.uploadFile = function(credited, url){
			console.log(vm.isactive);
			var formData = new FormData();
		    //Take the first selected file
		    console.log(vm.thisFile[0]);
		    formData.append("file", vm.thisFile[0]);
		    formData.append("credited", credited);
		    if(url && !/^(http):\/\//i.test(url)) {
                    url = 'http://' + url;
            }
		    formData.append("url", url);
		    formData.append('active', vm.isactive);


		    backgroundimage.saveBackgroundImage(formData).then(function(result) {
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

		vm.uploadcheck = function() {
			vm.uploading = true;
		}

    }

}());