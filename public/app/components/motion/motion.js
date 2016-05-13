(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('displayMotion', [
			'$stateParams',
			'Motion',
			displayMotion]);

	function displayMotion($stateParams, Motion) {

	  function MotionController($scope) {

	  		/**
	  		*	Prototypical function from Motion model to load motion. 
	  		*	Attempts to get from motionIndex object, if there is
	  		*	no motion it will pull from the API and create a new
	  		*   Motion model and reinsert that into the index.
	  		*/
			$scope.motion = Motion.get( $stateParams.id );

	    }

	    return {
	    	controller: ['$scope', MotionController],
	    	templateUrl: 'app/components/motion/motion.tpl.html'
	    }


	}


})();