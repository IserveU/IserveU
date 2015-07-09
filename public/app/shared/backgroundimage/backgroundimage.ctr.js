(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('BackgroundImageController', BackgroundImageController);

	function BackgroundImageController($scope, $http, $rootScope, $state, $location, auth, $mdDialog, $window ,UserbarService, backgroundimage) {	

		UserbarService.setTitle("Background Images");

		var vm = this;
		$scope.url = "http://";

		$scope.uploadFile = function(files, credited, url){
			var formData = new FormData();
		    //Take the first selected file
		    formData.append("file", files[0]);
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