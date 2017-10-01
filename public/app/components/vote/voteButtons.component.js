(function() {

  'use strict';

	angular
		.module('app.vote')
		.component('voteButtonsComponent', {
      require: { parent: '^^motionVotesComponent' },
      controller: VoteButtonsController,
      template: `
      <div class="motion_vote_buttons" layout="row" flex>
        <div ng-repeat="button in $ctrl.buttons" ng-hide="button.hidden" flex class="motion_vote_buttons__button--{{ button.disabled ? 'disabled':  button.type }}">
          <md-button aria-label="{{ ::button.ariaLabel }}" ng-mousedown="!button.disabled && button.cast($ctrl.parent.motion)" >
                <md-icon ng-class="button.selectedColour" md-svg-src="{{ button.selectedIcon }}"></md-icon>
          </md-button>
          <md-tooltip>{{ button.tooltip }}</md-tooltip>
        </div>
      </div>
      `
    });

  VoteButtonsController.$inject = ['$scope', '$interval','VoteResource','VoteButtons','Vote','ToastMessage'];
	function VoteButtonsController($scope, $interval, VoteResource, VoteButtons, Vote, ToastMessage) {

		// global context for 'this'
		var self = this;

		self.buttons = VoteButtons.buttons;

		/****************************************************
		*
		*	Function exports for controllerAs DOM access.
		*
		****************************************************/
		self.commonFunctions = {

			cast: function(motion) {
        Vote.validVote(motion, this.position);
        //Call if you can vote function
				castVote(this, motion._userVote, motion);
			},

			setActive: function() {
				this.selectedIcon = this.availableIcons.active;
				this.selectedClass = this.availableButtonClasses.active;
			},

			setLoading: function() {
				this.selectedIcon = this.availableIcons.loading;
				this.selectedClass = '';
			},

			setDefault: function() {
				this.selectedIcon = this.availableIcons.default;
				this.selectedClass = '';
			},

			setMessage: function(motion) {
				this.tooltip = VoteButtons.getMessage(motion, this.type);
			},

			setDisabled: function(motion) {
				this.disabled = !Vote.canVoteOn(motion);
			}
		};


    /****************************************************
    *
    * Initialization.
    *
    ****************************************************/
    self.$onInit = function() {
      var untilMotion = $interval(function(){
        if(self.parent.motion && self.parent.motion.hasOwnProperty('_motionOpenForVoting')) {
          var motion = self.parent.motion;
          for (var i in self.buttons) {
            if (self.buttons[i]) {
              var button = self.buttons[i];
              button.setMessage(motion);
              button.setDisabled(motion);
              $interval.cancel(untilMotion);
            }
          }
        }
      }, 500);

      angular.forEach(self.buttons, function(button, key) {
        angular.extend(button, self.commonFunctions);
        button.setDefault();
      });
    }

		/****************************************************
		*
		*	Private functions.
		*
		****************************************************/

		function castVote(button, vote, motion) {

			button.setLoading();

			var data = {
				id: vote ? vote.id : null,
				motion_id: motion.id,
				position: button.value
			}


			motion._rank -= (motion._userVote) ? motion._userVote.position : 0; // remove the old user position

  //    motion.rank -= motion._userVote.position; // remove the old user position

			motion._userVote = motion._userVote || {};
			motion._userVote.position = button.value;

      motion._rank += motion._userVote.position // add the new position

			if( !data.id ) {
				VoteResource.castVote(data).then(function(results) {
					successHandler(button, motion, results);
				}, button.setDefault());
			} else {
				VoteResource.updateVote(data).then(function(results) {
					successHandler(button, motion, results);
				}, button.setDefault());
			}

			motion.motionVotes.setOverallPositionLoading();
		}

		function successHandler(button, motion, vote) {
			Vote.voteSuccess(button.type);
			motion.reloadOnVoteSuccess( vote );
		}

    /** TODO: Make this into an EventEmiiter */
		$scope.$watch('motion._userVote', function(vote) {
			if( vote && vote.id ) {

				self.buttons.agree.setDefault();
				self.buttons.abstain.setDefault();
				self.buttons.disagree.setDefault();

				if(+vote.position === 0) {
					self.buttons.abstain.setActive();
				} else if (+vote.position === 1) {
					self.buttons.agree.setActive();
				} else if (+vote.position === -1) {
					self.buttons.disagree.setActive();
				}
			}
		}, true);

	}

})();
