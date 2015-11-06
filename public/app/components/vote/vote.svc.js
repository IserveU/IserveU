(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('VoteService', VoteService);

	function VoteService($stateParams, $timeout, vote, ToastMessage) {

		function calculateVotes(vote_array){

			var motionVotes = {
		            disagree:{percent:0,number:0},
		            agree:{percent:0,number:0},
		            abstain:{percent:0,number:0},
		            deferred_agree:{percent:0,number:0},
		            deferred_disagree:{percent:0,number:0},
		            deferred_abstain:{percent:0,number:0}
		        }

            if(vote_array[1]){
            	motionVotes.agree = ( vote_array[1].active ) ? vote_array[1].active  : motionVotes.agree; 
                motionVotes.deferred_agree = ( vote_array[1].passive ) ? vote_array[1].passive : motionVotes.deferred_agree;
            }
            if(vote_array[-1]){
                motionVotes.disagree = ( vote_array[-1].active ) ? vote_array[-1].active :  motionVotes.disagree;
                motionVotes.deferred_disagree = ( vote_array[-1].passive ) ? vote_array[-1].passive : motionVotes.deferred_disagree;
            }
            if(vote_array[0]){
            	motionVotes.abstain =  ( vote_array[0].active ) ? vote_array[0].active  : motionVotes.abstain;
                motionVotes.deferred_abstain = ( vote_array[0].passive ) ? vote_array[0].passive : motionVotes.deferred_abstain;
            }

            return motionVotes;

		}

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

            return motionVotes.position; 
		}

		return {
			showVoteMessage: showVoteMessage,
			overallMotionPosition: overallMotionPosition,
			calculateVotes: calculateVotes
		}

	}

})();