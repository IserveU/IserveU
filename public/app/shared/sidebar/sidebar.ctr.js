(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('SidebarController', SidebarController);

	function SidebarController(motion, $rootScope, $mdMedia, $mdSidenav) {

		var vm = this;
		var permissions = JSON.parse(localStorage.getItem('permissions'));

		vm.showUser = false;
		vm.emptyMotionsArray = false;

		$rootScope.$mdMedia = $mdMedia;

		vm.toggleSidenav = function(menuId){
			$mdSidenav('left').toggle();
		}

		if(permissions.indexOf("show-users") != -1) {
			vm.showUser = true;
		}



		vm.setMotionName = setMotionName;
		vm.getMotions = getMotions;
		$rootScope.userListIsClicked = false;


		$rootScope.$on('newMotion', function(events, data) {
			getMotions();
		});  


		$rootScope.$on('voteCast', function(events, data) {
			getMotions();
		});        	       
		
		$rootScope.$on('userListIsClicked', function(events, data) {
			$rootScope.userListIsClicked = true;
			getMotions();
		});        	       

		function setMotionName(title) {
			$rootScope.motionName = title;
		}

		function getMotions(){
			motion.getMotions().then(function(data) {
				if(!data[0]){
					vm.emptyMotionsArray = true;
				}
				vm.motions = data;
			});
		};

		getMotions();
	}

})();