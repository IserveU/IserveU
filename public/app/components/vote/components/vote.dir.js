(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('voteOnMotion', voteOnMotion);

  	 /** @ngInject */
	function voteOnMotion($rootScope, $stateParams, $timeout, vote, voteObj, motionObj, SetPermissionsService, voteButtonMessage, isMotionOpen) {


		function voteController($scope) {
			// variables
			var vm = this;
			vm.voting = {'1': false, '0':false, '-1': false};

			// DOM accessors for controller functions
			vm.castVote			 = castVote;
			vm.isVotingEnabled   = isVotingEnabled;
			vm.voteButtonMessage = voteButtonMessage;
			vm.voteObj			 = voteObj;

			// I wonder if I can share this via the quick-vote.dir.js
			function castVote(id, pos) {

				if( isVotingEnabled() )
					return 0;

				if( voteObj.user && voteObj.user.position != pos && voteObj.user.position != null) {
					vm.voting[pos] = true;
					updateVote(pos);
				}
				else {
					vm.voting[pos] = true;

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
				vm.voting[pos] = false;
				motionObj.reloadMotionObj(r.motion_id);
				voteObj.successFunc(r, pos, false);
			}

			function errorFunc(e, pos){
				vm.voting[pos] = false;
				ToastMessage.report_error(e);
			}

			function isVotingEnabled() {
				return !isMotionOpen.get() || !SetPermissionsService.can('create-votes');
			}

			$scope.$watch('v.voteObj.user', function(newValue, oldValue) {
				if( !angular.isUndefined(newValue) )
					// some sort of digest conflict, doesn't work without the slight 
					// offset of the timeout
					if(newValue.motion_id == $stateParams.id)
		            	$timeout(function() {
		                    voteObj.user  = newValue ? newValue : {position: null} ;     
							voteObj.calculateVotes(newValue.motion_id);
		            	}, 100);
			}, true);

			$rootScope.$on('usersVoteHasChanged', function(ev, data) {
				voteObj.user = data.vote;
			});
		}

		return {
			controller: voteController,
			controllerAs: 'v',
			templateUrl: 'app/components/vote/partials/vote.tpl.html'
		}

	}


})();