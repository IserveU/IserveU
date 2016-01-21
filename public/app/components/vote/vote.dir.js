(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('voteOnMotion', voteOnMotion);

	function voteOnMotion($stateParams, vote, voteObj, VoteService, motionObj, SetPermissionsService, ToastMessage) {


		function voteController($scope) {

			var vm = this;

			// variables
			var isMotionOpen = false;
			vm.voting = {'1': false, '0':false, '-1': false};

			// DOM accessors for controller functions
			vm.castVote			 = castVote;
			vm.isVotingEnabled   = isVotingEnabled;
			vm.voteButtonMessage = voteButtonMessage;
			vm.voteObj			 = voteObj;

			function castVote(id, pos) {

				if( isVotingEnabled() )
					return 0;

				var data = {
					motion_id: id,
					position: pos
				}

				if( vm.userVote && (vm.userVote.position != pos) ) {
					vm.voting[pos] = true;
					isMotionOpen = false;
					updateVote(pos);
				}
				else {
					vm.voting[pos] = true;
					isMotionOpen = false;
					vote.castVote(data).then(function(r) {
						successFunc(r, pos);
					}, function(e){ errorFunc(e, pos); });
				}
			}


			function updateVote(pos) {

				var data = {
					id: vm.userVote.id,
					position: pos
				}

				vote.updateVote(data).then(function(r) {
					successFunc(r, pos);
				}, function(e){ errorFunc(e, pos); });
			}

			function successFunc(r, pos){
				vm.voting[pos] = false;
				isMotionOpen = true;

				motionObj.reloadMotionObj(r.motion_id);
				voteObj.showMessage(pos);
				voteObj.calculateVotes(r.motion_id);	// vm.motionVotes will be an object Factory;
			}

			function errorFunc(e, pos){
				vm.voting[pos] = false;
				isMotionOpen = true;
				ToastMessage.report_error(e);
			}

			function isVotingEnabled() {
				return !isMotionOpen || !SetPermissionsService.can('create-votes');
			}

			function voteButtonMessage(type) {
				if ( !SetPermissionsService.can('create-votes') )
					return "You have not been verified as Yellowknife resident.";
				else if ( !isMotionOpen ) {
					for(var i in vm.voting) {
						if (vm.voting[i]) 
							return type;
					}
					return "This motion is closed.";
				}
				else
					return type;
			}

			$scope.$watch( function() { return motionObj.getMotionObj($stateParams.id); },
				function(motion) {
	                if( motion != null ) {
	                	isMotionOpen  = motion.MotionOpenForVoting
	                    vm.userVote   = motion.user_vote;
	                }
				}, true
			);

		}

		return {
			controller: voteController,
			controllerAs: 'c',
			templateUrl: 'app/components/vote/vote-production.tpl.html'
		}

	}


})();