 (function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('MotionSidebarController', MotionSidebarController);

	function MotionSidebarController(motion, $rootScope, $mdSidenav, $state) {

		var vm = this;

		vm.emptyMotionsArray = false;
		vm.isState = false;
		vm.filters = {
			take: 100,
			limit: 50,
		}

		$rootScope.$on('refreshMotionSidebar', function(events, data) {
			getMotions();
		});        	             	       

		function getMotions(){
			motion.getMotions(vm.filters).then(function(results) {
				if(!results.data[0]){
					console.log('here');
					vm.emptyMotionsArray = true;
				}
				vm.next_page = results.current_page + 1;
				vm.motions = results.data;
			});
		};

		function loadMoreMotions(){

			var data = vm.filters[page].push(vm.next_page);

			motion.getMotions(data).then(function(results) {
				if(!results.data[0]){
					vm.emptyMotionsArray = true;
				}
				vm.next_page = results.current_page + 1;
				vm.motions = results.data;
			});
		}

		function isStateNameMotion(){
			if($state.current.name == 'motion'){
				vm.isState = true;
			}
		}

		isStateNameMotion();
		getMotions();

	}



})();