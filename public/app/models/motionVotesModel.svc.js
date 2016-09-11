(function() {
	
angular
	.module('iserveu')
	.factory('MotionVotes', [
		'$translate',
		'motionVoteStatusbarService',
	function($translate, motionVoteStatusbarService) {

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

		var overallPosition = {
			default: {
				icon: 'thumbs-up-down',
				message: 'There have been no votes'
			},
			loading: {
				icon: 'loading',
				message: ''
			},
			agree: {
				icon: 'thumb-up',
				message: 'Most people agree'
			},
			disagree: {
				icon: 'thumb-down',
				message: 'Most people disagree'
			},
			abstain: {
				icon: 'thumbs-up-down',
				message: 'Most people abstained'
			},
			tie: {
				icon: 'thumbs-up-down',
				message: 'There was a tie'
			}
		}

		MotionVotes.prototype = {
			setData: function(motionVoteData) {
				angular.extend(this, motionVoteData);
				motionVoteStatusbarService.setStatusbar(this);
			},

			setOverallPosition: function(overallPosition) {
				console.log(overallPosition);
				this.setData({overallPosition: overallPosition});
			},

			setOverallPositionLoading: function() {
	            this.setData({ overallPosition: overallPosition.loading });	
			},

			getOverallPosition: function() {
				extractData(this);
			},

			reload: function(motionVoteData) {
				this.setData(motionVoteData);
				return this;
			}
		}

		function extractData(votes) {
			var $v = votes.data || votes;

			for(var i in $v) {
				i = +i;
			}

			function parse(key, type){
				return key && key.active.number;
			}

			var $d = angular.extend({}, {
				abstain:  parse($v[0]),
				agree:    parse($v[1]),
				disagree: parse($v[-1])
			});

			// literally every permutation ... hopefully.
			if( $d.disagree && ( !$d.agree || !$d.abstain ) ||
				$d.disagree > $d.agree && $d.disagree > $d.abstain ) {
	            votes.setOverallPosition( overallPosition.disagree );
				return 'disagree';
			} else if (  $d.agree && ( !$d.disagree || !$d.abstain ) ||
				$d.agree > $d.disagree && $d.agree > $d.abstain  ) {
			    votes.setOverallPosition( overallPosition.agree );
				return 'agree'
			} else if (  $d.abstain && ( !$d.disagree || !$d.agree ) ||
				$d.abstain > $d.disagree && $d.abstain > $d.agree  ) {
	            votes.setOverallPosition( overallPosition.abstain );
				return 'abstain';
			} else if ( !$d.abstain && !$d.agree && !$d.disagree ) {
				 votes.setOverallPosition( overallPosition.default );
				return 'no votes';
			} else if ( $d.abstain === $d.agree    && $d.abstain > $d.disagree || 
						$d.abstain === $d.disagree && $d.abstain > $d.agree    ||
						$d.agree   === $d.disagree && $d.agree > $d.abstain) {
				return 'tie';
			}
		}


		return MotionVotes;


}])

})();

