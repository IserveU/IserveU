(function() {

  'use strict';

  angular
    .module('iserveu')
    .directive('quickVote', motionSidebarQuickVote);

 /** @ngInject */
  function motionSidebarQuickVote($rootScope, $state, $translate, vote, voteObj, motionObj, ToastMessage, SetPermissionsService, SETTINGS_JSON) {

  	function controllerMethod() {

  		var vm = this;

        vm.canCreateVote = SetPermissionsService.can('create-votes');
        vm.cycleVote = cycleVote;
        vm.voteObj = voteObj;

		function cycleVote (motion){

			if(!$rootScope.userIsLoggedIn)
				ToastMessage.customFunction("You must be logged in to vote", "Login", 
					function(){
						$state.go('login');
					}, true);
			else if(!motion.MotionOpenForVoting)
				ToastMessage.simple("This " + $translate.instant('MOTION') + " is not open for voting.", 1000);
			else if(!SetPermissionsService.can('create-votes'))
				ToastMessage.simple("You must be a Yellowknife resident to vote.", 1000);
			else{ 
				if(!motion.user_vote){
					var pos = SETTINGS_JSON.abstain ? 0 : 1;
					castVote(motion.id, pos);
				}
				else{

					var data = {
		                id: motion.user_vote.id,
		                position: null
		            }
					
		            if(SETTINGS_JSON.abstain)
						if(motion.user_vote.position != 1)
							data.position = motion.user_vote.position + 1; 
						else
							data.position = -1;
					else
						data.position = motion.user_vote.position == 1 ? -1 : 1;

					updateVote(data);
				};
			};
		}

		function castVote(id, pos){
			vote.castVote({
				motion_id:id, 
				position:pos}).then(function(r){
				successFunc(r, pos);
			});
		}

		function updateVote(data){
			vote.updateVote(data).then(function(r) {
				successFunc(r, data.position);
			});
		}

		function successFunc(vote, pos) {
			motionObj.reloadMotionObj(vote.motion_id);
			voteObj.successFunc(vote, pos, true);
		}

  	}


    return {
    	controller: controllerMethod,
    	controllerAs: 'c',
    	templateUrl: 'app/components/motion/components/motion-sidebar/partials/quick-vote.tpl.html'
    }
    
  }
  
})();