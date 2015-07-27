(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('SidebarController', SidebarController);

	function SidebarController(motion, $rootScope) {

		var vm = this;
		var permissions = JSON.parse(localStorage.getItem('permissions'));

		vm.showUser = false;
		

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
			var input = {
				filter: "rank_greater_than"
			}
			motion.getMotions(input).then(function(data) {
				console.log(data);
				vm.motions = data;
			});
		};

		getMotions();
	}

})();