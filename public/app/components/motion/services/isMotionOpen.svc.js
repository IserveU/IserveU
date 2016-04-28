(function() {
	
	angular
		.module('iserveu')
		.service('isMotionOpen', isMotionOpen);

	function isMotionOpen() {
		
		var val = '';

		var review = false;

		this.get = function() {
			return val;
		}

		this.set = function(value) {
			val = value;
		}

		this.setStatus = function(value) {
			if (value == 1)
				review = true;
			else
				review = false;
		}

		this.isReview = function() {
			return review;
		}

	}


})();

