(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('displayMotion', displayMotion);


	//TODO: refactor
	 /** @ngInject */
	function displayMotion($rootScope, $stateParams, $mdToast, motion, motionObj, UserbarService, ToastMessage, voteObj, commentObj) {

	  function MotionController() {

	        var vm = this;

	        $rootScope.motionIsLoading[$stateParams.id] = true; // used to turn loading circle on and off for motion sidebar
	        vm.isLoading           = true; // Used to turn loading circle on and off for motion page
	        vm.motionDetail        = {};
	        vm.overallVotePosition = null;
	        vm.voteObj             = voteObj;
	        vm.editMode            = false;
	        vm.editMotion          = editMotion;
	        vm.cancelEditMotion    = cancelEditMotion;

	        /*********************************************** Motion Functions ****************************************** */

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
	        	UserbarService.title = motion.title;
	            vm.motionDetail = motion;
	            vm.isLoading = $rootScope.motionIsLoading[motion.id] = false;
	            commentObj.getMotionComments(motion.id);          
	        }

	        function editMotion(){
	            if(!vm.editMode)
	                UserbarService.title = "Edit: " + vm.motionDetail.title;

	            vm.editMode = !vm.editMode;
	        }

	        function cancelEditMotion(){

	            var toast = ToastMessage.action("Discard changes?", "Yes");

	            $mdToast.show(toast).then(function(response){
	                if(response == 'ok')
	                    editMotion();
	            });
	        }

	        getMotion($stateParams.id);

	        $rootScope.$on('initMotionOverallPosition', function(events, data){
	            vm.overallVotePosition = data.overall_position;
	        });

	    }


	    return {
	    	controller: MotionController,
	    	controllerAs: 'motion',
	    	templateUrl: 'app/components/motion/partials/motion.tpl.html'
	    }


	}


})();