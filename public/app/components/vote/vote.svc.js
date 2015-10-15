(function() {

	'use strict';

	angular
		.module('iserveu')
		.service('VoteService', VoteService);

	function VoteService($stateParams, vote, ToastMessage) {

		var vm = this;

		vm.userVoteId;

		function showVoteMessage(position, voting) {

			var message = "You ";

			switch(position){
				case -1:
					message = message+"disagreed with ";
					voting.disagree = true;
					break;
				case 1:
					message = message+"agreed with ";
					voting.agree = true;
					break;
				case 0:
					message = message+"abstain with ";
					voting.abstain = true;
			}
			
			ToastMessage.simple(message+"this motion");

		}

		function getUsersVotes() {

			vote.getUsersVotes().then(function(result){
				angular.forEach(result, function(value, key) {
					if(value.motion_id == $stateParams.id){
		              vm.usersVote = parseInt(value.position);
          			  vm.userHasVoted = true;
          			  vm.userVoteId = value.id;
					}
				});
			});
		}

		getUsersVotes();

		return {
			showVoteMessage: showVoteMessage,
			getUsersVotes: getUsersVotes
		}

	}

})();