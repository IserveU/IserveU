 (function() {

	'use strict';

	angular
		.module('iserveu')
		.controller('MotionSidebarController', MotionSidebarController);

	function MotionSidebarController(motion, vote, $rootScope) {

		var vm = this;

		vm.emptyMotionsArray = false;

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


		// make this into a service maybe?
		vm.cycleVote = function(motion){
			if(motion.votes[0] == undefined){
				castVote(motion.id);
			}

			else{
				var data = {
	                id: motion.votes[0].id,
	                position: null
	            }
				if(motion.votes[0].position != 1){
					data.position = motion.votes[0].position + 1; 
				}
				else {
					data.position = -1;
				}

				updateVote(data);
			}

		}

		function castVote(id){
			// start at abstain
			vote.castVote({motion_id:id, position:0}).then(function(result){
				getMotions();
			});
		}

		function updateVote(data){
			vote.updateVote(data).then(function(result) {
				getMotions();
			});
		}

		getMotions();

	}



})();