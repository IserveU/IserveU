(function() {

	'use strict';

	angular
		.module('iserveu')
		.service('VoteService', VoteService);


	function VoteService($stateParams, vote) {

		var vm = this;

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


        getUsersVotes();     

        return {
        	getUsersVotes: getUsersVotes,
        	showCommentVoteColumn: showCommentVoteColumn
        }

	}


}());