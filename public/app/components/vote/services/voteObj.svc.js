(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('voteObj', voteObj);

	function voteObj($stateParams, vote, ToastMessage) {

	
		var voteObj = {
			motionVotes: {
		            disagree:{percent:0,number:0},
		            agree:{percent:0,number:0},
		            abstain:{percent:0,number:0},
		            deferred_agree:{percent:0,number:0},
		            deferred_disagree:{percent:0,number:0},
		            deferred_abstain:{percent:0,number:0}
		    },
		    votes: {},
		    calculateVotes: function(id) {
		    	// TODO: figure out how to make this DOM obj not disappear each time a user votes.
		    	for(var i in voteObj.motionVotes) {
		    		for(var n in voteObj.motionVotes[i]) {
		    			voteObj.motionVotes[i][n] = 0;
		    		}
		    	}

		    	vote.getMotionVotes(id).then(function(r){
		    		var votes = voteObj.votes = r.data;


		            if(votes[1]){
		            	voteObj.motionVotes.agree = ( votes[1].active ) ? votes[1].active  : voteObj.motionVotes.agree; 
		                voteObj.motionVotes.deferred_agree = ( votes[1].passive ) ? votes[1].passive : voteObj.motionVotes.deferred_agree;
		            }
		            if(votes[-1]){
		                voteObj.motionVotes.disagree = ( votes[-1].active ) ? votes[-1].active :  voteObj.motionVotes.disagree;
		                voteObj.motionVotes.deferred_disagree = ( votes[-1].passive ) ? votes[-1].passive : voteObj.motionVotes.deferred_disagree;
		            }
		            if(votes[0]){
		            	voteObj.motionVotes.abstain =  ( votes[0].active ) ? votes[0].active  : voteObj.motionVotes.abstain;
		                voteObj.motionVotes.deferred_abstain = ( votes[0].passive ) ? votes[0].passive : voteObj.motionVotes.deferred_abstain;
		            }

		            return voteObj.motionVotes;

	            });
		    },
		    showMessage: function(pos) {
				pos = pos == 1 ? 'agreed with' : ( pos == 0 ? 'abstained on' : 'disagreed with');
				ToastMessage.simple( 'You ' + pos + " this motion" );
		    },
		    getOverallPosition: function() {

		    	var position;

	            if(voteObj.motionVotes.disagree.number > voteObj.motionVotes.agree.number)
	                position = "thumb-down";
	            else if(voteObj.motionVotes.disagree.number < voteObj.motionVotes.agree.number)
	                position = "thumb-up";
	            else
	                position = "thumbs-up-down";

	            return position; 

		    }
		};

		voteObj.calculateVotes($stateParams.id);

		return voteObj;


	}

})();