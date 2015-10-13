(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('VoteService', VoteService);

	function VoteService($stateParams, $timeout, vote, ToastMessage) {

		function showVoteMessage(position, voting) {

			var message = "You ";

			switch(position){
				case -1:
					message = message+"disagreed with";
					voting.disagree = true;
					break;
				case 1:
					message = message+"agreed with";
					voting.agree = true;
					break;
				case 0:
					message = message+"abstain with";
					voting.abstain = true;
			}
			
			ToastMessage.simple( message + " this motion" );

		}

		function overallMotionPosition(motionVotes){

            if(motionVotes.disagree.number>motionVotes.agree.number){
                motionVotes.position = "thumb-down";
            } else if(motionVotes.disagree.number<motionVotes.agree.number){
                motionVotes.position = "thumb-up";
            } else {
                motionVotes.position = "thumbs-up-down";
            } 
		}

		return {
			showVoteMessage: showVoteMessage,
			overallMotionPosition: overallMotionPosition,
		}

	}

})();