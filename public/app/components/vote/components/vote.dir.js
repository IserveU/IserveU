(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('voteOnMotion', 
			['$rootScope', '$stateParams', '$state', '$timeout', 'vote', 
			'voteObj', 'motionObj', 'Authorizer', 
			'voteButtonMessage', 'isMotionOpen', 'ToastMessage'
			, voteOnMotion]);

	// Still a todo.
  	 /** @ngInject */
	function voteOnMotion($rootScope, $stateParams, $state, $timeout, vote, voteObj, motionObj, Authorizer, voteButtonMessage, isMotionOpen, ToastMessage) {


		function voteController($scope) {

			// global moving reference to this
			var self = this;

			// variables
			self.voting = {'1': false, '0':false, '-1': false};

			// controllers exports for DOM accessor
			self.castVote			 = castVote;
			self.isVotingEnabled     = isVotingEnabled;
			self.voteButtonMessage   = voteButtonMessage;
			self.voteObj			 = voteObj;

			function castVote(id, pos) {

				/**
				*	Removed for localized economies.
				*/
				// if( isVotingEnabled() )
				// 	return 0;
				if(!$rootScope.userIsLoggedIn){
					ToastMessage.customFunction("You must be logged in to vote", "Go", 
						function(){
							$state.go('login');
						}, true);
					return 0;
				}
				
				self.voting[pos] = true;

				if( voteObj.user && voteObj.user.position != pos && voteObj.user.position != null) {
					updateVote(pos);
				}
				else {
					vote.castVote({
						motion_id: id,
						position: pos
					}).then(function(r) {
						successFunc(r, pos);
					}, function(e){ errorFunc(e, pos); });
				}
			}


			function updateVote(pos) {
				vote.updateVote({
					id: voteObj.user.id,
					position: pos
				}).then(function(r) {
					successFunc(r, pos);
				}, function(e){ errorFunc(e, pos); });
			}
 
			function successFunc(r, pos){
				self.voting[pos] = false;
				motionObj.reloadMotionObj(r.motion_id);
				
				$timeout(function(){
					voteObj.successFunc(r, pos, false);
				}, 100);
			}

			function errorFunc(e, pos){
				self.voting[pos] = false;
				ToastMessage.report_error(e);
			}

			function isVotingEnabled() {
				return !isMotionOpen.get() || !Authorizer.canAccess('create-votes') || isMotionOpen.isReview();
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
			controller: ['$scope', voteController],
			controllerAs: 'v',
			templateUrl: 'app/components/vote/partials/vote.tpl.html'
		}

	}


})();