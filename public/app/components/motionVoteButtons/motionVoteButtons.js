(function () {
	angular
		.module('iserveu')
		.directive('motionVoteButtons',
		['$interval',
			'voteResource',
			'ToastMessage',
			'motionVoteButtonService',
			'Authorizer',
			'voteModel',
			motionVoteButtons])

	function motionVoteButtons($interval, voteResource, ToastMessage, motionVoteButtonService, Authorizer, voteModel) {
		function motionVoteButtonsController($scope) {
			// global context for 'this'
			var self = this

			self.buttons = motionVoteButtonService.buttons

			/****************************************************
			*
			*Function exports for controllerAs DOM access.
			*
			****************************************************/
			self.commonFunctions = {

				cast: function (motion) {
					if (!voteModel.validVote(motion, this.position)) return;

					// Call if you can vote function

					castVote(this, motion._userVote, motion)
				},

				setActive: function () {
					this.selectedIcon = this.availableIcons.active
					this.selectedClass = this.availableButtonClasses.active
				},

				setLoading: function () {
					this.selectedIcon = this.availableIcons.loading
					this.selectedClass = ''
				},

				setDefault: function () {
					this.selectedIcon = this.availableIcons.default
					this.selectedClass = ''
				},

				setMessage: function (motion) {
					this.tooltip = motionVoteButtonService.getMessage(motion, this.type)
				},

				setDisabled: function (motion) {
					this.disabled = !voteModel.canVoteOn(motion)
				}
			}

			/****************************************************
			*
			*Private functions.
			*
			****************************************************/

			function castVote(button, vote, motion) {
				button.setLoading()



				var data = {
					id: vote ? vote.id : null,
					motion_id: motion.id,
					position: button.value
				}

				motion._rank -= (motion._userVote) ? motion._userVote.position : 0 // remove the old user position

				// motion.rank -= motion._userVote.position; // remove the old user position

				motion._userVote = motion._userVote || {}
				motion._userVote.position = button.value

				motion._rank += motion._userVote.position // add the new position

				if (!data.id) {
					voteResource.castVote(data).then(function (results) {
						successHandler(button, motion, results)
					}, button.setDefault())
				} else {
					voteResource.updateVote(data).then(function (results) {
						successHandler(button, motion, results)
					}, button.setDefault())
				}

				motion.motionVotes.setOverallPositionLoading()
			}

			function successHandler(button, motion, vote) {
				voteModel.voteSuccess(button.type)
				motion.reloadOnVoteSuccess(vote)
			}

			$scope.$watch('motion._userVote', function (vote) {
				if (vote && vote.id) {
					self.buttons.agree.setDefault()
					self.buttons.abstain.setDefault()
					self.buttons.disagree.setDefault()

					if (+vote.position === 0) {
						self.buttons.abstain.setActive()
					} else if (+vote.position === 1) {
						self.buttons.agree.setActive()
					} else if (+vote.position === -1) {
						self.buttons.disagree.setActive()
					}
				}
			}, true);

			(function init() {
				var untilMotion = $interval(function () {
					if ($scope.motion && $scope.motion.hasOwnProperty('_motionOpenForVoting')) {
						var motion = $scope.motion
						for (var i in self.buttons) {
							if (self.buttons[i]) {
								var button = self.buttons[i]
								button.setMessage(motion)
								button.setDisabled(motion)
								$interval.cancel(untilMotion)
							}
						}
					}
				}, 500)

				angular.forEach(self.buttons, function (button, key) {
					angular.extend(button, self.commonFunctions)
					button.setDefault()
				})
			})()
		}

		return {
			controller: ['$scope', motionVoteButtonsController],
			controllerAs: 'motionVoteButtons',
			templateUrl: 'app/components/motionVoteButtons/motionVoteButtons.tpl.html'
		}
	}
})()
