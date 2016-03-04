(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('voteObj', voteObj);

  	 /** @ngInject */
	function voteObj($rootScope, commentObj, $stateParams, vote, ToastMessage) {

		var factory = {
			user: { position: null },
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
		    	for(var i in this.motionVotes) {
		    		for(var j in this.motionVotes[i])
		    			this.motionVotes[i][j] = 0;
		    	}

		    	vote.getMotionVotes(id).then(function(r){

		    		var votes = factory.votes = r.data;

		            if(votes[1]){
		            	factory.motionVotes.agree = ( votes[1].active ) 
		            								? votes[1].active  
		            								: factory.motionVotes.agree; 
		                factory.motionVotes.deferred_agree = ( votes[1].passive ) 
		                									 ? votes[1].passive 
		                									 : factory.motionVotes.deferred_agree;
		            }
		            if(votes[-1]){
		                factory.motionVotes.disagree = ( votes[-1].active ) 
		                							   ? votes[-1].active 
		                							   : factory.motionVotes.disagree;
		                factory.motionVotes.deferred_disagree = ( votes[-1].passive ) 
		                										? votes[-1].passive 
		                										: factory.motionVotes.deferred_disagree;
		            }
		            if(votes[0]){
		            	factory.motionVotes.abstain =  ( votes[0].active ) 	
		            								   ? votes[0].active  
		            								   : factory.motionVotes.abstain;
		                factory.motionVotes.deferred_abstain = ( votes[0].passive ) 
		                									   ? votes[0].passive 
		                									   : factory.motionVotes.deferred_abstain;
		            }

		            return factory.motionVotes;
	            });
		    },
		    showMessage: function(pos) {
				pos = pos == 1 
					  ? 'agreed with' 
					  : ( pos == 0 ? 'abstained on' : 'disagreed with');
				
				ToastMessage.simple( 'You ' + pos + " this motion" );
		    },
		    getOverallPosition: function() {
		    	var position;

	            if(this.motionVotes.disagree.number > this.motionVotes.agree.number)
	                position = "thumb-down";
	            else if(this.motionVotes.disagree.number < this.motionVotes.agree.number)
	                position = "thumb-up";
	            else
	                position = "thumbs-up-down";

	            return position; 
		    },
		    successFunc: function(vote, pos, quickVote) {
		    	if(!quickVote){
					factory.user = vote;
					factory.calculateVotes(vote.motion_id);
		    	}

				factory.showMessage(pos);

				commentObj.getMotionComments(vote.motion_id);  
				$rootScope.$broadcast('usersVoteHasChanged', {vote: vote});
		    }
		};

		factory.calculateVotes($stateParams.id);

		return factory;	
	}

})();