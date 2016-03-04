(function() {

  'use strict';

  angular
    .module('iserveu')
    .directive('quickVote', motionSidebarQuickVote);

 /** @ngInject */
  function motionSidebarQuickVote(vote, voteObj, motionObj, ToastMessage, SetPermissionsService, incompleteProfileService) {

  	function controllerMethod() {

  		var vm = this;

        vm.canCreateVote = SetPermissionsService.can('create-votes');
        vm.cycleVote = cycleVote;
        vm.voteObj = voteObj;

		function cycleVote (motion){

			if(!motion.MotionOpenForVoting)
				ToastMessage.simple("This motion is not open for voting.", 1000);
			else if( incompleteProfileService.check() )
				ToastMessage.simple("Complete your profile before voting.", 1000);
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
			vote.castVote({
				motion_id:id, 
				position:0}).then(function(r){
				successFunc(r, 0, true);
			});
		}

		function updateVote(data){
			vote.updateVote(data).then(function(r) {
				successFunc(r, 0, data.position);
			});
		}

		function successFunc(vote, pos) {
			motionObj.reloadMotionObj(vote.motion_id);
			voteObj.successFunc(vote, pos, true);
		}

  	}


    return {
    	controller: controllerMethod,
    	controllerAs: 'c',
    	templateUrl: 'app/components/motion/components/motion-sidebar/partials/quick-vote.tpl.html'
    }
    
  }
  
})();