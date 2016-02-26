(function() {

  'use strict';

  angular
    .module('iserveu')
    .directive('quickVote', motionSidebarQuickVote);

 /** @ngInject */
  function motionSidebarQuickVote($rootScope, vote, voteObj, motionObj, ToastMessage, SetPermissionsService) {

  	function controllerMethod() {

  		var vm = this;

        vm.canCreateVote = SetPermissionsService.can('create-votes');
        vm.cycleVote = cycleVote;

		function cycleVote (motion){

			if(!motion.MotionOpenForVoting)
				ToastMessage.simple("This motion is not open for voting.", 1000);
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
				successFunc(r.motion_id, 0);
			});
		}

		function updateVote(data){
			vote.updateVote(data).then(function(r) {
				successFunc(r.motion_id, data.position);
			});
		}

		function successFunc(id, pos) {

			$rootScope.$broadcast('usersVoteHasChanged');

			motionObj.reloadMotionObj(id);
			voteObj.calculateVotes(id);
			voteObj.showMessage(pos);
		}
  	}


    return {
    	controller: controllerMethod,
    	controllerAs: 'c',
    	templateUrl: 'app/components/motion/components/motion-sidebar/partials/quick-vote.tpl.html'
    }
    
  }
  
})();