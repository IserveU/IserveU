(function() {

  'use strict';

  angular
    .module('app.vote')
    .component('quickVoteComponent', {
      template: `
      <md-button aria-label="User Menu" class="md-icon-button" ng-mousedown="motion._motionOpenForVoting && $ctrl.cycleVote(motion)" ng-switch="motion._userVote.position">

          <md-icon ng-switch-when="1" class="md-primary" ng-class="{'quick-vote-disabled':!motion._motionOpenForVoting}" md-svg-src="thumb-up"  aria-label="cycle vote" ></md-icon>

          <md-icon ng-if="$ctrl.allowAbstain" ng-switch-when="0" ng-class="{'quick-vote-disabled':!motion._motionOpenForVoting}" md-svg-src="thumbs-up-down" aria-label="cycle vote" ng-disabled="!motion._motionOpenForVoting"></md-icon>

          <md-icon ng-switch-when="-1" class="md-accent" ng-class="{'quick-vote-disabled':!motion._motionOpenForVoting}" md-svg-src="thumb-down"  aria-label="cycle vote" ng-disabled="!motion._motionOpenForVoting"></md-icon>

          <md-icon ng-switch-when="undefined" class="mdi mdi-med mdi-checkbox-blank-circle-outline" ng-class="{'quick-vote-disabled':!motion._motionOpenForVoting}" aria-label="cycle vote"></md-icon>

        <md-tooltip>{{ motion._motionOpenForVoting ? 'Quick Vote' : ( 'MOTION' | translate ) + ' is closed to voting.' }}</md-tooltip>

      </md-button>`,
      controller: QuickVoteController
    });

  QuickVoteController.$inject = ['Vote', 'Settings'];

	function QuickVoteController(Vote, Settings) {

    this.allowAbstain = Settings.get('voting.abstain');
    this.cycleVote = cycleVote;

		function cycleVote(motion) {

      var position = calculateNewUserPosition(motion);

      if (!Vote.validVote(motion, position)) {
        return false;
      }

      Vote.vote(motion, position);
		}
    
    function calculateNewUserPosition(motion) {
      // Start by voting `for`
      if(!motion._userVote || motion._userVote && !motion._userVote.id) {
        return 1;
      }   
      if (this.allowAbstain && motion._userVote.position === 1) {
        return 0;
      }
      if (motion._userVote.position === 0) {
        return -1;
      }
      return (motion._userVote.position * -1);
    }

  }
})();
