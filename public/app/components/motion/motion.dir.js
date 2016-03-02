(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('displayMotion', displayMotion);

	 /** @ngInject */
	function displayMotion($stateParams, motion, motionObj, voteObj, commentObj, motionFilesFactory) {

	  function MotionController($scope) {

	  		/* Variables */
			$scope.votes   = voteObj;
			$scope.motion  = motionObj;
			$scope.motionFile = motionFilesFactory;

			/**
			*	Function to retrieve motion from existing array.
			*	If it has not been receieved from the API yet, 
			*	it will do a single pull.
			*/
	        function getMotion(id) {

	            var catchMotion = motionObj.getMotionObj(id);

	            motionObj.details   = null;
	            motionObj.isLoading = true;
	            commentObj.comment  = null;

	            if (catchMotion) 
	                motionObj.setMotionDependencies(catchMotion)
	            else 
	                motion.getMotion(id).then(function(r) {
	                    motionObj.setMotionDependencies(r);
	                });     
	        }

	        getMotion($stateParams.id);
	    }


	    return {
	    	controller: MotionController,
	    	templateUrl: 'app/components/motion/partials/motion.tpl.html'
	    }


	}


})();