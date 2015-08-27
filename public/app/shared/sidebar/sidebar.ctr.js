(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('SidebarController', SidebarController);

	function SidebarController(motion, $rootScope, $mdMedia, $mdSidenav) {

		var vm = this;

		vm.emptyMotionsArray = false;

		$rootScope.$mdMedia = $mdMedia;

		vm.toggleSidenav = function(menuId){
			$mdSidenav('left').toggle();
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
		
		function setMotionName(title) {
			$rootScope.motionName = title;
		}

		function getMotions(){
			var filters = {
				take: 100,
				limit: 50
			}
			motion.getMotions(filters).then(function(results) {
				if(!results.data[0]){
					vm.emptyMotionsArray = true;
				}
				vm.next_page = results.current_page + 1;
				vm.motions = results.data;
			});
		};

		function loadMoreMotions(){
			var data = {
				take: 100,
				limit: 50,
				page: vm.next_page
			}
			motion.getMotions(data).then(function(results) {
				if(!results.data[0]){
					vm.emptyMotionsArray = true;
				}
				vm.next_page = results.current_page + 1;
				vm.motions = results.data;
			});
		}

		getMotions();
	}

})();