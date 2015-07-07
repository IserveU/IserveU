(function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('SidebarController', SidebarController);

	function SidebarController(motion, $rootScope) {

		var vm = this;
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
				vm.motions = data;
			});
		};

		getMotions();
	}

})();