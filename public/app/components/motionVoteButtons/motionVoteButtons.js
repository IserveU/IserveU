(function() {
	
	angular
		.module('iserveu')
		.directive('motionVoteButtons', 
			['$rootScope', 
			 '$state', 
			 '$translate', 
			 'SETTINGS_JSON', 
			 'voteResource', 
			 'Authorizer', 
			 'ToastMessage', 
			 'errorHandler', motionVoteButtons]);

	function motionVoteButtons($rootScope, $state, $translate, SETTINGS_JSON, voteResource, Authorizer, ToastMessage, errorHandler) {

		function motionVoteButtonsController() {

			// global context for 'this'
			var self = this;

			var iconUrl = 'icons/';
			var parentClass = 'motionVotes__buttons--';
			var ariaLabel = 'Vote button to ';

			/****************************************************
			*	
			*	Function exports for controllerAs DOM access. 
			*
			****************************************************/

			self.buttons = {

				agree: {
					type: 'agree',
					class: parentClass + 'agree',
					hidden: false,
					icon: iconUrl +'thumb-up-outline',
					iconClass: 'md-primary',
					message: 'Agree with this ',
					value: 1,
					activeIcon: iconUrl + 'thumb-up',
					activeClass: 'raised',
					originalIcon: iconUrl + 'thumb-up-outline'
				},

				abstain: {
					type: 'abstain',
					class: parentClass + 'abstain',
					hidden: !SETTINGS_JSON.abstain || false,
					icon: iconUrl + 'thumbs-up-down-outline',
					iconClass: '',
					message: 'Abstain on this ',
					value: 0,
					activeIcon: iconUrl + 'thumbs-up-down',
					activeClass: 'raised',
					originalIcon: iconUrl + 'thumbs-up-down-outline'
				},

				disagree: {
					type: 'disagree',
					class: parentClass + 'disagree',
					hidden: false, 
					icon: iconUrl + 'thumb-down-outline',
					iconClass: 'md-accent',
					message: 'Disagree with this ',
					value: -1,
					activeIcon: iconUrl + 'thumb-down',
					activeClass: 'raised',
					originalIcon: iconUrl + 'thumb-down-outline',
				}
			};

			self.commonButtonFunctions = {
				ariaLabel: 'Vote button to ' + this.type,
				isDisabled: function(motion) {
					return isVotingEnabled(motion);
				},

				isActiveClass: function(userVote) {
					userVote = userVote || {position: null};

					if( userVote.position == this.value ) {
						this.icon = this.activeIcon;
						return true;
					}

					return false;
				},

				cast: function(motion) {

					var userVote = motion.user_vote || {position: null};
					castVote(this, userVote, motion);

					return true;
				},

				getMessage: function(motion) {
					return isVotingEnabled(motion) ? getDisabledMessage(motion) :
							this.message + $translate.instant('MOTION');
				} 
			};

			angular.forEach(self.buttons, function(value, key) {
				angular.extend(value, self.commonButtonFunctions);
			});

			/****************************************************
			*	
			*	Private functions. 
			*
			****************************************************/

			function castVote(button, userVote, motion) {

				if(!$rootScope.userIsLoggedIn){
					ToastMessage.mustBeLoggedIn('to vote');
					return false;
				}

				if( isVotingEnabled(motion) )
					return false;

				if(userVote.position == button.value)
					return false;
				
				button.icon = 'loading';
				motion.motionVotes.setOverallPositionLoading();


				if( userVote && userVote.position != button.value && userVote.position != null) {
					voteResource.updateVote({
						id: userVote.id,
						position: button.value
					}).then(function(results) {

						angular.forEach(self.buttons, function(type, key){
							if(type.value == userVote.position) {
								type.icon = angular.copy(type.originalIcon);
							}
						});

						successHandler(button, motion, results);

					}, function(error){ 

						button.icon = angular.copy(button.originalIcon);
						errorHandler(error); 
					});
				}
				else {
					voteResource.castVote({
						motion_id: motion.id,
						position: button.value
					}).then(function(results) {

						button.icon = angular.copy(button.activeIcon);
						successHandler(button, motion, results);

					}, function(error){ 

						button.icon = angular.copy(button.originalIcon);
						errorHandler(error); 
					});
				}
			}

			function successHandler(button, motion, results) {

				button.icon = angular.copy(button.activeIcon);

				ToastMessage.voteSuccess(button.type);

				// reloads dependencies
				motion.getMotionComments();
				motion.getMotionVotes();
				motion.reloadMotionIndex();
				motion.reloadUserVote(results);

			}

			function getDisabledMessage(motion) {

				if (!$rootScope.userIsLoggedIn)

					return "You must login before you can vote.";

				else if ( !Authorizer.canAccess('create-vote') )

					return "You do not have permission to vote.";

				else if ( motion.status === 1 ) 
				
					return "This "+ $translate.instant('MOTION') + " is currently being reviewed and is not open for voting.";
				
				else if ( !motion.MotionOpenForVoting )

					return "This "+ $translate.instant('MOTION') + " is closed for voting.";

			}


			function isVotingEnabled(motion) {
				return ( !motion.MotionOpenForVoting || 
					 	 !Authorizer.canAccess('create-vote') || 
					 	 motion.status === 1 );
			}


		}


		return {
			controller: motionVoteButtonsController,
			controllerAs: 'motionVoteButtons',
			templateUrl: 'app/components/motionVoteButtons/motionVoteButtons.tpl.html'
		}


	}

})();