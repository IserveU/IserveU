(function() {
	
angular
	.module('iserveu')
	.factory('MotionVotes', ['$translate', function($translate) {

		function MotionVotes(motionVoteData) {

			// I wonder if this is something i should be doing,
			// because ... one of hte pros of using models
			// is to have contracts; so if something doesn't
			// exist then to destroy it?
			if(motionVoteData) {
				this.setData(motionVoteData);
			} 

			this.overallPosition = {};
		}

		var iconUrl = 'icons/';

		var overallPosition = {
			default: {
				icon: iconUrl + 'thumbs-up-down',
				message: 'There have been no votes'
			},
			loading: {
				icon: 'loading',
				message: ''
			},
			agree: {
				icon: iconUrl + 'thumb-up',
				message: 'Most people agree'
			},
			disagree: {
				icon: iconUrl + 'thumb-down',
				message: 'Most people disagree'
			},
			abstain: {
				icon: iconUrl + 'thumbs-up-down',
				message: 'This ' + $translate.instant('MOTION') + ' has resulted in a tie.'
			}
		}

		MotionVotes.prototype = {

			setData: function(motionVoteData) {
				angular.extend(this, motionVoteData);
			},

			setOverallPosition: function(overallPosition) {
				console.log(overallPosition);
				this.setData({overallPosition: overallPosition});
			},

			setOverallPositionLoading: function() {
	            this.setData({overallPosition: overallPosition.loading});	
			},

			getOverallPosition: function() {
				getOverallPosition(this);
			}

		}

		// private function this is breaking ... working on it in JSBIN TODO:
		function getOverallPosition(votes) {
			console.log(votes);
			if(!votes['-1'] && !votes['1'] && !votes['0']) {
	            votes.setOverallPosition( overallPosition.default );
			}
	    	else if (votes['-1'] && !votes['1'] && !votes['0']) {
	            votes.setOverallPosition( overallPosition.disagree );
	    	}
	    	else if (!votes['-1'] && votes['1'] && !votes['0']) {
	            votes.setOverallPosition( overallPosition.agree );
	    	}
	        else if (votes['0'] && !votes['-1'] && !votes['1']) {
	            votes.setOverallPosition( overallPosition.abstain );
	        }
	    	else if (votes['-1'].active.number > votes['1'].active.number) {
	    		console.log('onehere');
	            votes.setOverallPosition( overallPosition.disagree );
	    	}
			else if (votes['-1'] && !votes['1']) {
	            votes.setOverallPosition( overallPosition.disagree );
	    	}
	    	else if (!votes['-1'] && votes['1']) {
	            votes.setOverallPosition( overallPosition.agree );
	    	}
	    	else if (votes['-1'].active.number < votes['1'].active.number) {
	            votes.setOverallPosition( overallPosition.agree );
	    	}
	    	else {
	            votes.setOverallPosition( overallPosition.abstain );
	    	}
		}

		return MotionVotes;


}])

})();

