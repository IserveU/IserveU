(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('sharedVoteService', sharedVoteService);

	function sharedVoteService($stateParams, $http, vote) {
		
		var vm = this;

		vm.votes = null;

		vm.data = {
			usersVote: null, userHasVoted: null, userVoteId: null
		}

		function getUserVote(){
			return $http.get('api/vote/').then(function(result){
				vm.votes = result;
				console.log(vm.votes);
				getData();
				console.log($stateParams.id);
			})
		}

		function getData(){
			var i;
			var truthy = false;

			if(vm.votes == null){
				getUserVote();
			}
			else{
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
	      			  vm.data.userHasVoted = false;
	      			  vm.data.userVoteId = null;
				}
			}
		}

		return {
			getUsersVotes: getUserVote,
			getData: getData,
			getHasVoted: function() {
				if(vm.data.userHasVoted == null){
					getData();
				}
				else{
					return vm.data.userHasVoted;
				}
			},
			data: vm.data
		}

	}


})();