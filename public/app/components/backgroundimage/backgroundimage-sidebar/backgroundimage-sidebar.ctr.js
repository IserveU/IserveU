(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('BackgroundImageSidebarController', backgroundSidebar);

		function backgroundSidebar($rootScope, $scope, backgroundimage){

			var vm = this;

			function backgroundImages(){
				backgroundimage.getBackgroundImages().then(function(result) {
					vm.backgroundimages = result.data;
				}, function(error) {
					console.log(error);
				});
			}

			$rootScope.$on('backgroundImageUpdated', function(event, data) {
				backgroundImages();
			});

			backgroundImages();

		}

	}
)();