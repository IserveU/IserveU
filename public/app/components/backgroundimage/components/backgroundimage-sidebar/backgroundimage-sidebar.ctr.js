(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('BackgroundImageSidebarController', backgroundSidebar);

		/** @ngInject */
		function backgroundSidebar($rootScope, $scope, $filter, backgroundimage, ToastMessage){

			var vm = this;

			vm.activateMotion = activateMotion;

			function backgroundImages(){
				backgroundimage.getBackgroundImages().then(function(result) {
					vm.backgroundimages = result.data;
					checkIfDisplayed(vm.backgroundimages);
				}, function(error) {
					console.log(error);
				});
			}

			function activateMotion(id, active){
				var data = {
					id: id,
					active: active
				}

				backgroundimage.updateBackgroundImage(data).then(function(result){
				}, function(error){
					ToastMessage.report_error();
				})
			}

			function checkIfDisplayed(){

				var todayDate = new Date();
				todayDate = $filter('date')(todayDate, "yyyy-MM-dd HH:mm:ss");

				angular.forEach(vm.backgroundimages, function(value, key){
					var counter = 0;
					angular.forEach(value, function(type, key){
						if(key == 'active' && type == 1){
							counter++;
						}
						if(key == 'display_date' && type <= todayDate){
							counter++;
						}
					})
					if(counter == 2){
						value['hasBeenDisplayed'] = true;
						vm.hasBeenDisplayed = true;
					}
				})
			}

			$rootScope.$on('backgroundImageUpdated', function(event, data) {
				backgroundImages();
			});

			backgroundImages();

		}

	}
)();