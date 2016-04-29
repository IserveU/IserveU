(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('displayMotion', [
			'Authorizer', '$state',
			'$stateParams', 'motion', 'motionObj', 'voteObj', 'commentObj', 'motionFilesFactory',
			displayMotion]);

	 /** @ngInject */
	function displayMotion(Authorizer, $state, $stateParams, motion, motionObj, voteObj, commentObj, motionFilesFactory) {

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
	        function getMotion(id, mData) {


	            var motionData = mData || motionObj.getMotionObj(id);

	            if(motionData && motionData.status < 1 && !Authorizer.canAccess('administrate-motion')){
	            	$state.go('home');
				}

	            motionObj.clearMotionDependencies();

				// If is a promise, then call self to resolve.
	     		if(motionData.hasOwnProperty('$$state')) 
	     			motionData.then(function(mData){ return getMotion(id, mData);});
	     		else
	     			motionObj.setMotionDependencies(motionData);
	        }

	        getMotion($stateParams.id);
	    }


	    return {
	    	controller: ['$scope', MotionController],
	    	templateUrl: 'app/components/motion/partials/motion.tpl.html',
	    	link: function(scope, el, attrs) {
	    		scope.$on('$destroy', function() {
	    			voteObj.clear();
	    			commentObj.clear();
	    		});
	    	}
	    }


	}


})();