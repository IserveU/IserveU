(function() {
  
  'use strict';

  angular
    .module('app.vote')
    .component('voteStatusbarComponent', {
      controller: ['$scope', VoteStatusbarController],
      require: { parent: '^^motionVotesComponent' },
      template: `
      <div class="motion_vote_statusbar" layout="row" layout-align="center center" flex>
          <div ng-if="$ctrl.statusbar.agree" style="flex-basis:{{ $ctrl.statusbar.agree.percent }}%" class="md-button md-raised md-primary motion_vote_statusbar__bar--agree" aria-label="Number in agreement">
            <md-tooltip>{{ $ctrl.statusbar.agree.number }} agreed</md-tooltip>
          </div>

          <div ng-if="$ctrl.allowAbstain" style="flex-basis:{{ $ctrl.statusbar.abstain.percent }}%" class="md-button md-raised md-grey motion_vote_statusbar__bar--abstain" aria-label="Number abstaining">
            <md-tooltip>{{ $ctrl.statusbar.abstain.number  }} abstained</md-tooltip>
          </div>

          <div ng-if="$ctrl.statusbar.disagree" style="flex-basis:{{ $ctrl.statusbar.disagree.percent }}%" class="md-button md-raised md-accent motion_vote_statusbar__bar--disagree" aria-label="Number in disagreement">
            <md-tooltip>{{ $ctrl.statusbar.disagree.number }} disagreed</md-tooltip>
          </div>
      </div>
      `
    });

  VoteStatusbarController.$inject = ['$scope', '$interval','VoteStatusbar','Settings','Utils'];
  function VoteStatusbarController($scope, $interval, VoteStatusbar, Settings, Utils) {

    /** global context for `this` */
    var self = this;
    /** allow abstain component to render */
    self.allowAbstain = Settings.get('voting.abstain');

    /** TODO: make this into an EventEmitter */
    $scope.$watch('$ctrl.parent.motion.motionVotes', function(newValue, oldValue) {
      if(newValue && oldValue !== newValue) {
        self.statusbar = VoteStatusbar.getStatusbar();
      }
    }, true);

    self.$onInit = function() {
      var waitUntil = $interval(function() {
        if(!Utils.objectIsEmpty(VoteStatusbar.getStatusbar())){
          self.statusbar = VoteStatusbar.getStatusbar();
          $interval.cancel(waitUntil);
        }
      }, 500);     
    }
    
  }

})();