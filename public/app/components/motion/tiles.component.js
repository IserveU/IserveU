(function() {

  'use strict';

  angular
    .module('app.motions')
    .component('motionTilesComponent', {
      controller: MotionTilesController,
      require: { parent: '^^motionComponent' },
      template: `
      <md-list flex>
        <md-list-item class="md-2-line motion_tiles__department">
          <md-icon id="department_icon" md-svg-icon="{{ ::$ctrl.parent.motion.department.icon }}"></md-icon>
            <div class="md-list-item-text ">
              <h3>{{ $ctrl.parent.motion.department.name }}</h3>
              <p>Department</p>
            </div>
        </md-list-item>

        <md-list-item class="md-2-line motion_tiles__closing" id="closing_tile" ng-if="$ctrl.allowClosingMotions">
          <md-icon ng-if="!$ctrl.parent.motion._motionOpenForVoting" md-svg-icon="calendar-remove"></md-icon>
              <div ng-if="!$ctrl.parent.motion._motionOpenForVoting" class="md-list-item-text ">
                <h3>{{'CLOSED' | translate}}</h3>
                <p>{{ 'CLOSES_ON' | translate }}</p>
              </div>

            <md-icon ng-if="$ctrl.parent.motion._motionOpenForVoting" md-svg-icon="calendar-clock"></md-icon>
              <div ng-if="$ctrl.parent.motion._motionOpenForVoting && $ctrl.parent.motion.closing_at.alpha_date" class="md-list-item-text ">
                <h3>{{ $ctrl.parent.motion.closing_at.alpha_date | customLongDate }}</h3>
                <p>{{ 'CLOSES_ON' | translate }}</p>
              </div>

              <div ng-if="$ctrl.parent.motion._motionOpenForVoting && !$ctrl.parent.motion.closing_at.alpha_date" class="md-list-item-text">
                <h3>Undetermined</h3>
                <p>{{ 'CLOSES_ON' | translate }}</p>
              </div>
        </md-list-item>


        <md-list-item class="md-2-line motion_tiles__passing-status">
            <md-icon id="passing_status_icon" md-svg-src="{{ $ctrl.parent.motion.rankTile.icon || 'loading' }}"></md-icon>
              <div class="md-list-item-text" >
                <h3>{{ $ctrl.parent.motion.rankTile.message }}</h3>
                <p>Status</p>
              </div>
        </md-list-item>

      </md-list>`
    });

  MotionTilesController.$inject = ['$scope', '$mdMedia', 'Settings'];
  function MotionTilesController($scope, $mdMedia, Settings) {

    var self = this;

    self.allowClosingMotions = Settings.get('motion.allow_closing');

    $scope.$watch('$ctrl.parent.motion._rank', function() {
      self.parent.motion.rankTile = getRankTile(self.parent.motion._rank);
    }, false);

    // what is this being used for?
    $scope.direction = $mdMedia('gt-sm') ? 'left' : '';

    function getRankTile(rank) {

      var overallPosition = {
        loading: {
          icon: 'loading',
          message: ''
        },
        agree: {
          icon: 'thumb-up',
          message: 'Majority agree'
        },
        disagree: {
          icon: 'thumb-down',
          message: 'Majority disagree'
        },
        tie: {
          icon: 'thumbs-up-down',
          message: 'Majority tie'
        }
      };

      if (rank < 0 )
        return overallPosition.disagree;
      if (rank > 0)
        return overallPosition.agree;
      if (rank === 0)
        return overallPosition.tie;

      return overallPosition.loading;
    }



  }

})();
