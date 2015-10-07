(function() {

	'use strict';

	angular
    .module('iserveu')
    .service('VoteService', VoteService);


  function VoteService($stateParams, vote, ToastMessage) {

    var vm = this;

    vm.showVoteMessage = showVoteMessage;

    function showVoteMessage(position, voting){
        
        var message = "You";

        switch(position){
          case -1:
            message = message+" disagreed with this motion";
            voting.disagree = true;
            break;
          case 1:
            message = message+" agreed with this motion";
            voting.agree = true;
            break;
          default:
            message = message+" abstained from voting on this motion";
            voting.abstain = true;
        }

      ToastMessage.simple(message);

    }

    function getUsersVotes() {

      vote.getUsersVotes().then(function(result) {
        angular.forEach(result, function(value, key) {
          if(value.motion_id == $stateParams.id) {
            vm.usersVote = parseInt(value.position);
            vm.userHasVoted = true;
            vm.userVoteId = value.id;
          }
        });
      });
    }  

    function showCommentVoteColumn(){
      var result = false;
      if(vm.usersVote == 1){
        result = true;
      }
       return result;
      } 

  return {
     getUsersVotes: getUsersVotes,
     showCommentVoteColumn: showCommentVoteColumn,
     showVoteMessage: showVoteMessage
  }

}


}());