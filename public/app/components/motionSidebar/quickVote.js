(function() {

  'use strict';

  angular
    .module('iserveu')
    .directive('quickVote', [
    	'$rootScope', 
    	'$state',
    	'$stateParams',
    	'$translate', 
    	'voteResource',
    	'Motion',
    	'motionIndex',
    	'ToastMessage', 
    	'Authorizer', 
    	'SETTINGS_JSON',
	quickVote]);

  function quickVote($rootScope, $state, $stateParams, $translate, voteResource, Motion, motionIndex, ToastMessage, Authorizer, SETTINGS_JSON) {

  	function quickVoteController() {

  		var self = this;

        self.cycleVote = cycleVote;

		function cycleVote (motion){

			if(!$rootScope.userIsLoggedIn){

				ToastMessage.mustBeLoggedIn('to vote');
			
			}
			else if(!motion.MotionOpenForVoting){

				ToastMessage.simple("This " + $translate.instant('MOTION') + " is not open for voting.", 1000);
			
			}
			else if(!Authorizer.canAccess('create-vote')){

				ToastMessage.simple("You must be a Yellowknife resident to vote.", 1000);
			
			}
			else { 
				if(!motion.user_vote){

					var position = SETTINGS_JSON.abstain ? 0 : 1;
					castVote( motion, position );
				
				}
				else {

		            var position, data;
					
		            if(SETTINGS_JSON.abstain) {
						position = motion.user_vote.position != 1 ? (motion.user_vote.position + 1) : -1;
					}
					else {
						position = motion.user_vote.position == 1 ? -1 : 1;
					}

					updateVote({
						id: motion.user_vote.id,
						position: position
					}, motion);
				};
			};
		}

		/******************************************************************
		*
		*	Private functions
		*
		*******************************************************************/
		
		function castVote(motion, pos){
			voteResource.castVote({
				motion_id: motion.id, 
				position: pos
			}).then(function(r){
				successHandler(r, pos, motion);
			});
		}

		function updateVote(data, motion){
			voteResource.updateVote(data).then(function(r) {
				successHandler(r, data.position, motion);
			});
		}

		function successHandler(vote, pos, motion) {

			if( !(motion instanceof Motion) ){
				motion = Motion.build(motion);
			}

			motion.reloadUserVote( vote );
			motion.getMotionVotes();
			motionIndex.reloadOne( motion );

			if(motion.id == $stateParams.id) {
				$state.reload();
			}

		}

  	}


    return {
    	controller: quickVoteController,
    	controllerAs: 'quickVote',
    	templateUrl: 'app/components/motionSidebar/quickVote.tpl.html'
    }
    
  }
  
})();