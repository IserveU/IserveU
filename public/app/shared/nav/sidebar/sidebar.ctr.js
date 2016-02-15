(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('SidebarController', SidebarController);

	function SidebarController(motion, $rootScope, $mdSidenav, $mdMedia) {

		var vm = this;

		$rootScope.$mdMedia = $mdMedia;
		vm.keepOpen = false;

		vm.toggleSidenav = function(menuId){
			$mdSidenav(menuId).toggle().then(function(){
				vm.keepOpen = !$rootScope.keepOpen;
			});
		}

		vm.closeSidenav = function(menuId){
			$mdSidenav(menuId).close().then(function(){
				vm.keepOpen = false;
			});
		}


		vm.testingInfinite = function(){
			console.log('triggered');
		}

	}

})();