(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('voteService', [
			'$rootScope',
			'Authorizer',
			'$translate',
			'SETTINGS_JSON',
      'ToastMessage',
      'voteResource',
      'Motion',
		voteService]);

	function voteService($rootScope, Authorizer, $translate, SETTINGS_JSON, ToastMessage, voteResource, Motion) {


		function validVote(motion,position) {
  
      //You needed to be logged in
      if(ToastMessage.mustBeLoggedIn('to vote.')){
        return false;
      }
      
      //You voted this way already
      if(motion._userVote && motion._userVote.position === position){
        return false;
      }
      
      //Motion isn't open for voting
      if(!isVotingEnabled(motion)){
        ToastMessage.translate('MOTION_CLOSED');
        return false;
      }
      
      // User doesn't have permission to vote
      if(!ToastMessage.mustHavePermission('create-vote')){
        return false;
      }
      
      return true;
		}
    
    /**
     * Is this used?
     * @param  {[type]}  status [description]
     * @return {Boolean}        [description]
     */
    function isMotionDraft(status) {
      return ['draft', 'review'].indexOf(status) >= 0;
    }  
    
    function voteSuccess(type) {
      ToastMessage.simple('You ' + type + (type === 'abstain' ? 'ed on' : 'd with') +
        ' this ' + $translate.instant('MOTION'));
    }

    
    function canVoteOn(motion){

      if(!Authorizer.canAccess('create-vote')){ return false; }

      return isVotingEnabled(motion);
    }

		function isVotingEnabled(motion) {
      if(!motion._motionOpenForVoting){
        return false;
      }

      return true;
		}
    
    function vote(motion, position){
      	if(!motion._userVote || (motion._userVote && !motion._userVote.id)){
          return castVote(motion, position)
        }
        
        return updateVote(motion, position);
    }
    
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
    
		return {
			isVotingEnabled: isVotingEnabled,
      canVoteOn: canVoteOn,
      validVote: validVote,
      voteSuccess: voteSuccess,
      vote: vote
		}
	}


})();

