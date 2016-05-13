(function() {

'use strict';

angular
.module('iserveu')
.factory('MotionComments', ['$http', '$translate', function($http, $translate) {

	function MotionComments(motionVoteData) {

		if(motionVoteData) {
			this.setData(motionVoteData);
		} 
		
	}

	MotionComments.prototype = {

		setData: function(motionVoteData) {
			angular.extend(this, motionVoteData);
		},

		setAgreeComments: function() {

		},

		setDisagreeComments: function() {

		}
	}


	return MotionComments;


}])

})();

