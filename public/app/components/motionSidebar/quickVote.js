(function() {

  'use strict';

  angular
    .module('iserveu')
    .directive('quickVote', [
    	'$translate', 
    	'voteResource',
    	'Motion',
    	'ToastMessage', 
    	'Authorizer', 
    	'SETTINGS_JSON',
	quickVote]);

  function quickVote($translate, voteResource, Motion, ToastMessage, Authorizer, SETTINGS_JSON) {

  	function quickVoteController() {

        this.cycleVote = cycleVote;

		function cycleVote (motion){

			if(ToastMessage.mustBeLoggedIn('to vote')) {
				return; 
			}
			else if(!motion.motionOpenForVoting) {
				ToastMessage.simple("This " + $translate.instant('MOTION') + " is not open for voting.", 1000);
			}
		 	else if(!Authorizer.canAccess('create-vote')) {
				ToastMessage.simple("You must be a Yellowknife resident to vote.", 1000);
			}
			else { 
				if( !motion.userVote && !motion.userVote.id ){
					var position = SETTINGS_JSON.abstain ? 0 : 1;
					castVote(motion, position);
				}
				else {

		            var position, data;
					
		            if(SETTINGS_JSON.abstain) {
		            	// increment without including abstain value == 0
						position = motion.userVote.position != 1 ? (motion.userVote.position + 1) : -1;
					}
					else {
						// increment normally
						position = motion.userVote.position == 1 ? -1 : 1;
					}

					updateVote(motion, position);
				};
			};
		}

		/******************************************************************
		*
		*	Private functions
		*
		*******************************************************************/
		
		function castVote(motion, position){
			voteResource.castVote({
				motion_id: motion.id, 
				position: position,
			}).then(function(r){
				successHandler(r, motion);
			});
		}

		function updateVote(motion, position){
			voteResource.updateVote({
				id: motion.userVote.id,
				position: position,
			}).then(function(r) {
				successHandler(r, motion);
			});
		}

		function successHandler(vote, motion) {
			if( !(motion instanceof Motion) ){
				motion = Motion.build(motion);
			}
			motion.reloadOnVoteSuccess( vote );
		}

  	}


    return {
    	controller: quickVoteController,
    	controllerAs: 'quickVote',
    	templateUrl: 'app/components/motionSidebar/quickVote.tpl.html'
    }
    
  }
  
})();