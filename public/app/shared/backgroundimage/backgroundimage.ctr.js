(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('BackgroundImageController', BackgroundImageController);

	function BackgroundImageController($scope, $rootScope, $state, $mdToast, UserbarService, backgroundimage) {	

		UserbarService.setTitle("Background Images");

		var vm = this;
		$scope.url = ""; // Jessica: This means that http comes through on the front end and the placeholder doesn't work, it's better to append this for the user or always strip it off and then add it.
		vm.thisFile;
		var settings = JSON.parse(localStorage.getItem('settings'));
		$scope.backgroundcredits = settings.background_image;

		$scope.uploading = false;
		$scope.onSuccess = false;
		$scope.showError = false;


		$scope.chosenImage = function(files){
			vm.thisFile = files;
		}

		$scope.uploadFile = function(credited, url){
			var formData = new FormData();
		    //Take the first selected file
		    console.log(vm.thisFile[0]);
		    formData.append("file", vm.thisFile[0]);
		    formData.append("credited", credited);
		    if(url && !/^(http):\/\//i.test(url)) {
                    url = 'http://' + url;
            }
		    formData.append("url", url);


		    backgroundimage.saveBackgroundImage(formData).then(function(result) {
		    	$scope.onSuccess = true;
		    	$scope.uploading = false;
		    	$scope.uploadfile.credits.$setPristine();
		    	$mdToast.show(
                  $mdToast.simple()
                    .content('Your image has been sent in for approval!')
                    .position('bottom right')
                    .hideDelay(3000)
                );
		    },function(error){
		    	console.log("error");
		    	$scope.uploading = false;
		    	$scope.showError = true;
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

		$scope.uploadcheck = function() {
			$scope.uploading = true;
		}

    }

}());