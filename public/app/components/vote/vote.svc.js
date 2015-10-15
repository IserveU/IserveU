(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('VoteService', VoteService);

	function VoteService($stateParams, $timeout, vote, ToastMessage) {

		function showVoteMessage(position) {

			var message = "You ";

			switch(position){
				case -1:
					message = message+"disagreed with";
					break;
				case 1:
					message = message+"agreed with";
					break;
				case 0:
					message = message+"abstain with";
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