(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('voteObj', voteObj);

  	 /** @ngInject */
	function voteObj($rootScope, $translate, commentObj, $stateParams, vote, ToastMessage, utils) {

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
		    overallPosition: null,
		    voteLoading: true,
		    calculateVotes: function(id) {
		    	vote.getMotionVotes(id).then(function(r){
		    		factory.getOverallPosition(factory.votes);
		    		return factory.votes = r.data;
	            });
		    },
		    showMessage: function(pos) {
				pos = pos == 1 
					  ? 'agreed with' 
					  : ( pos == 0 ? 'abstained on' : 'disagreed with');
				
				ToastMessage.simple( 'You ' + pos + " with this " + $translate.instant('MOTION') );
		    },
		    getOverallPosition: function(votes) {

		    	if(votes)
		    		this.votes = votes; 

		    	if(!this.votes['-1'] && !this.votes['1'])
	                this.overallPosition = "thumbs-up-down";
		    	else if (this.votes['-1'] && !this.votes['1'])
		    		this.overallPosition = "thumb-down";
		    	else if (!this.votes['-1'] && this.votes['1'])
		    		this.overallPosition = "thumb-up";
		    	else if (this.votes['-1'].active.number > this.votes['1'].active.number)
		    		this.overallPosition = "thumb-down";
		    	else if (this.votes['-1'].active.number < this.votes['1'].active.number)
		    		this.overallPosition = "thumb-up";
		    	else 
	                this.overallPosition = "thumbs-up-down";

				factory.voteLoading = false; 
				return this.overallPosition;
		    },
		    successFunc: function(vote, pos, quickVote) {

				factory.showMessage(pos);

		    	if(!quickVote){
					factory.user = vote;
					factory.calculateVotes(vote.motion_id);
		    	}

				if($stateParams.id == vote.motion_id){
					factory.voteLoading = true;
					commentObj.getMotionComments(vote.motion_id);  
					$rootScope.$broadcast('usersVoteHasChanged', {vote: vote});
				}
		    },
			clear: function() {
				this.user = {postion:null};
				this.votes = {};
				this.overallPosition = null;
				this.voteLoading = true;
			}
		};

		if($stateParams.id)
			factory.calculateVotes($stateParams.id);

		return factory;	
	}

})();