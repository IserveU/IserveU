(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('voteOnMotion', voteOnMotion);

	function voteOnMotion($rootScope, $stateParams, vote, motion, voteObj, motionObj, commentObj, SetPermissionsService, ToastMessage) {


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

				if( voteObj.user && voteObj.user.position != pos && voteObj.user.position != null) {
					vm.voting[pos] = true;
					isMotionOpen = false;
					updateVote(pos);
				}
				else {
					vm.voting[pos] = true;
					isMotionOpen = false;

					vote.castVote({
						motion_id: id,
						position: pos
					}).then(function(r) {
						successFunc(r, pos);
					}, function(e){ errorFunc(e, pos); });
				}
			}


			function updateVote(pos) {

				var data = {
					id: voteObj.user.id,
					position: pos
				}

				vote.updateVote(data).then(function(r) {
					successFunc(r, pos);
				}, function(e){ errorFunc(e, pos); });
			}

			function successFunc(r, pos){

				$rootScope.$broadcast('usersVoteHasChanged');

				vm.voting[pos] = false;
				isMotionOpen = true;

				commentObj.getMotionComments(r.motion_id);  // this does not seem to work with $watch in another directive. still doesn't belong here though.
			
				voteObj.user = r;
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
					for(var i in vm.voting) 
						if ( vm.voting[i] ) return type;
					return "This motion is closed.";
				}
				else return type;
			}

			// TODO: refactor this beast.
			// $scope.$watch( function() { return motionObj.getMotionObj($stateParams.id); },
			// 	function(motionDetail) {
	  //               if( motionDetail != null ) {
	  //               	isMotionOpen  = motionDetail.MotionOpenForVoting
	  //                   voteObj.user  = motionDetail.user_vote;
	  //               } else 
	  //               	motion.getMotion($stateParams.id).then(function(r) {
		 //                	isMotionOpen  = r.MotionOpenForVoting
		 //                    voteObj.user  = r.user_vote ? r.user_vote : {position: null} ;
	  //               	});
			// 	}, true
			// );

		}

		return {
			controller: voteController,
			controllerAs: 'v',
			templateUrl: 'app/components/vote/partials/vote.tpl.html'
		}

	}


})();