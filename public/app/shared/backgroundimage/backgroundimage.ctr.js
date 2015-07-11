(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('BackgroundImageController', BackgroundImageController);

	function BackgroundImageController($scope, $http, $rootScope, $state, $location, auth, $mdDialog, $window ,UserbarService, backgroundimage) {	

		UserbarService.setTitle("Background Images");

		var vm = this;
		$scope.url = ""; // Jessica: This means that http comes through on the front end and the placeholder doesn't work, it's better to append this for the user or always strip it off and then add it.
		vm.thisFile;

		$scope.chosenImage = function(files){
			vm.thisFile = files;
		}

		$scope.uploadFile = function(credited, url){
			var formData = new FormData();
		    //Take the first selected file
		    console.log(vm.thisFile[0]);
		    formData.append("file", vm.thisFile[0]);
		    formData.append("credited", credited);
		    formData.append("url", url);


		    backgroundimage.saveBackgroundImage(formData).then(function(result) {
		    	$state.go($state.current, {}, {reload: true});
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

		function getBackgroundImages(){
			backgroundimage.getBackgroundImages().then(function(result) {
				console.log(result);
			});
		}

		getBackgroundImages();

    }

}());