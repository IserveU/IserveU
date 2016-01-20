(function() {

  'use strict';

  angular
    .module('iserveu')
    .directive('quickVote', motionSidebarQuickVote);

  function motionSidebarQuickVote(vote, SetPermissionsService) {

  	console.log('quickVote');
  	function controllerMethod() {

  		var vm = this;

        vm.canCreateVote     = SetPermissionsService.can('create-votes');

		// make this into a directive, or a switch 
		vm.cycleVote = function(motion){

			if(!motion.MotionOpenForVoting)
				ToastMessage.simple("Sorry! This motion is not open for voting.", 1000);
			else{ 
				if(!motion.user_vote)
					castVote(motion.id);
				else{

					var data = {
		                id: motion.user_vote.id,
		                position: null
		            }
					
					if(motion.user_vote.position != 1)
						data.position = motion.user_vote.position + 1; 
					else
						data.position = -1;

					updateVote(data);
				};
			};

		}

		function castVote(id){
			vote.castVote({motion_id:id, position:0}).then(function(r){
				// getMotions();
				// getMotionInsideVoteController(r);
			});
		}

		function updateVote(data){
			vote.updateVote(data).then(function(result) {
				// getMotions();
				// getMotionInsideVoteController(result);
			});
		}

		function getMotionInsideVoteController(result) {
			// if($stateParams.id == result.motion_id){
			// 	$rootScope.$emit('getMotionInsideVoteController', {vote:result});
			// }
		}

		// getMotions();

		// $rootScope.$on('refreshMotionSidebar', function(events, data) {
		// 	getMotions(vm.motion_filters);
		// });

		// $rootScope.$on('refreshSelectMotionOnSidebar', function(events, data){
		// 	angular.forEach(vm.motions, function(value, key) {
		// 		if(value.id == data.motion.id){
		// 			vm.motions[key] = data.motion;
		// 		}
		// 	})
		// })     
  	}




    return {
    	controller: controllerMethod,
    	controllerAs: 'c',
    	templateUrl: 'app/components/motion/components/motion-sidebar/quick-vote.tpl.html'
    }
    
  }
  
})();