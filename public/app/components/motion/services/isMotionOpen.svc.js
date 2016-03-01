(function() {
	
	angular
		.module('iserveu')
		.service('isMotionOpen', isMotionOpen);

	function isMotionOpen() {
		
		var val = '';

		this.get = function() {
			return val;
		}

		this.set = function(value) {
			val = value;
		}

	}


})();

