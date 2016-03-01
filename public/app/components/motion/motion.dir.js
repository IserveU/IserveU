(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('displayMotion', displayMotion);


	//TODO: refactor
	 /** @ngInject */
	function displayMotion($rootScope, $stateParams, motion, motionObj, UserbarService, voteObj, commentObj, isMotionOpen) {

	  function MotionController() {

	        $rootScope.motionIsLoading[$stateParams.id] = true; // used to turn loading circle on and off for motion sidebar

	  		/* Variables */
	  		var vm = this;
			vm.details = {};
			vm.isLoading = true;
			vm.voteObj = voteObj;

	        function getMotion(id) {

	            var catchMotion = motionObj.getMotionObj(id);

	            commentObj.comment = null;

	            if (catchMotion) 
	                postGetMotion(catchMotion)
	            else {
	                motion.getMotion(id).then(function(r) {
	                    postGetMotion(r);
	                });     
	            }
	        }

	        function postGetMotion(motion){
	        	// service setters
	        	UserbarService.title = motion.title;
	            isMotionOpen.set(motion.MotionOpenForVoting);
	            voteObj.user  = motion.user_vote ? motion.user_vote : {position: null} ;

	            // UI animation and dependencies
	            vm.details = motion;
	            vm.isLoading    = $rootScope.motionIsLoading[motion.id] = false;
	            commentObj.getMotionComments(motion.id);  
	            voteObj.calculateVotes(motion.id);   
	        }

	        getMotion($stateParams.id);
	        
	    }


	    return {
	    	controller: MotionController,
	    	controllerAs: 'motion',
	    	templateUrl: 'app/components/motion/partials/motion.tpl.html'
	    }


	}


})();