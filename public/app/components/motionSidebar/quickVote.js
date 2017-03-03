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
        this.isMotionDraft = isMotionDraft;

		function cycleVote (motion){

      var position, data;

			if(ToastMessage.mustBeLoggedIn('to vote')) {
				return;
			}
			else if(!motion._motionOpenForVoting) {
				ToastMessage.simple("This " + $translate.instant('MOTION') + " is not open for voting.", 1000);
			}
		 	else if(!Authorizer.canAccess('create-vote')) {
				ToastMessage.simple("You aren't able to vote until your account is authorized", 1000);
			}
			else {
				if( !motion._userVote || motion._userVote && !motion._userVote.id ){
					position = SETTINGS_JSON.voting.abstain ? 0 : 1;
					castVote(motion, position);
				}
				else {
          if(SETTINGS_JSON.voting.abstain) {
          	// increment without including abstain value == 0
						position = motion._userVote.position !== 1 ? (motion._userVote.position + 1) : -1;
					}
					else {
						// increment normally
						position = motion._userVote.position === 1 ? -1 : 1;
					}

					updateVote(motion, position);
				}
			}
		}

    function isMotionDraft(status) {

      return ['draft', 'review'].indexOf(status) >= 0;
    }

		/******************************************************************
		*
		*	Private functions
		*
		*******************************************************************/

		function castVote(motion, position){
			voteResource.castVote({
				motion_id: motion.id,
				position: position
			}).then(function(r){
				successHandler(r, motion);
			});
		}

		function updateVote(motion, position){
			voteResource.updateVote({
				id: motion._userVote.id,
				position: position
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
