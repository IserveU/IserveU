(function() {

  'use strict';

  angular
    .module('iserveu')
    .directive('quickVote', [
    	'$translate',
    	'ToastMessage',
    	'Authorizer',
    	'SETTINGS_JSON',
      'voteModel',
	quickVote]);

  function quickVote($translate, ToastMessage, Authorizer, SETTINGS_JSON, voteModel) {

  	function quickVoteController() {

    this.cycleVote = cycleVote;

		function cycleVote (motion){

      var position = calculateNewUserPosition(motion);

      if(!voteModel.validVote(motion, position)){
        return false;
      }

      voteModel.vote(motion, position);
		}
    
    function calculateNewUserPosition(motion){
  
      //Start by voting for
      if(!motion._userVote || motion._userVote && !motion._userVote.id){
        return 1;
      }
          
      if(SETTINGS_JSON.voting.abstain && motion._userVote.position ===1){
        return 0;
      }
      
      if(motion._userVote.position === 0){
        return -1;
      }
      
      return (motion._userVote.position * -1);
    }

  }

  return {
  	controller: quickVoteController,
  	controllerAs: 'quickVote',
  	templateUrl: 'app/components/motionSidebar/quickVote.tpl.html'
  }


}})();
