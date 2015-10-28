(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('BackgroundImageController', BackgroundImageController);

	function BackgroundImageController($rootScope, $state, $scope, $timeout, ToastMessage, UserbarService, SetPermissionsService, backgroundimage) {	

		UserbarService.setTitle("Upload");

		var vm = this;

		$scope.$state = $state;

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

		vm.uploadFile = function(){

		    backgroundimage.saveBackgroundImage(vm.thisFile).then(function(result) {

		    	vm.backgroundimages = '';

		    	vm.onSuccess = true;
		    	vm.uploading = false;
		    	ToastMessage.double("Upload successful!", "Your image has been sent in for approval!", vm.isNotAdmin);

				$rootScope.$emit('backgroundImageUpdated');

		    },function(error){
		    	vm.uploading = false;
		    	vm.showError = true;
		    });

		}

		vm.upload = function(flow){
			vm.preview = true;

			var fd = new FormData();

			fd.append("background_images", flow.files[0].file);
		    fd.append("credited", vm.credited);
		    if ( vm.url && !/^(http):\/\//i.test(vm.url) ) {
		    	vm.url = 'http://' + vm.url; // appends http: if missing
		    }
		    fd.append("url", vm.url);
		    fd.append('active', vm.isactive);

		    vm.thisFile = fd;

		}

		isAdmin();


    }

}());