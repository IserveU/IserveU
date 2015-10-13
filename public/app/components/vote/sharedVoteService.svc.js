(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('sharedVoteService', sharedVoteService);

	function sharedVoteService($stateParams, $http, vote) {
		
		var vm = this;

		vm.data = {
			usersVote: null, userHasVoted: null, userVoteId: null
		}

		function getUserVote(){
			return $http.get('api/vote/').success(function(result){
				vm.votes = result;
				getData();
			})
		}

		function getData(){
			var i;
			var truthy = false;

			for(i = 0; i < vm.votes.length; i++){
				if(vm.votes[i].motion_id == $stateParams.id){
				  truthy = true;
	              vm.data.usersVote = parseInt(vm.votes[i].position);
      			  vm.data.userHasVoted = true;
      			  vm.data.userVoteId = vm.votes[i].id;
				}
			}
			if(!truthy){
	              vm.data.usersVote = null;
      			  vm.data.userHasVoted = null;
      			  vm.data.userVoteId = null;
			}
		}


		return {
			getUsersVotes: getUserVote,
			getData: getData,
			data: vm.data
		}

	}


})();