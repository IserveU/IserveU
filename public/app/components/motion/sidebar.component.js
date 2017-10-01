(function() {

  'use strict';

  angular
    .module('app.motions')
    .component('motionSidebarComponent', {
      controller: MotionSidebarController,
      template: `
        <md-content class="flex md-default-theme motion-sidebar">
          <motion-search-component hide-gt-lg hide-lg hide-gt-md hide-md flex></motion-search-component>
            <div infinite-scroll="$ctrl.loadMotions()"
                 infinite-scroll-container="'.site-sidenav'"
                 infinite-scroll-distance="0"
                 infinite-scroll-disabled="( $ctrl.motionIndex._paginating || $ctrl.motionIndex._stopPaginating)">

              <md-list>
                  <md-list-item layout="row" class="sidebar-list-item" ng-repeat="motion in $ctrl.motionIndex._index" ui-sref-active="active" ui-sref="motion({id:motion.slug})" ng-click="$ctrl.closeSidenav('left')">
                    <md-icon class="department" md-svg-src="{{ ::motion.department.icon }}">
                      <md-tooltip md-direction="right">{{ ::motion.department.name }}</md-tooltip>
                    </md-icon>
                    <p class="md-body-1 ellipsis">{{ ::motion.title }}</p>
                    <quick-vote-component ng-if="$root.userIsLoggedIn && $ctrl.showVoteView"></quick-vote-component>
                  </md-list-item>

                  <md-list-item ng-if="$ctrl.motionIndex._index.length == 0 && !$ctrl.motionIndex._paginating">
                    <p class="md-body-1 ellipsis" translate="{{'NO_MOTIONS'}}"></p>
                  </md-list-item>

                  <md-list-item has-permission="create-motion" layout="row" class="sidebar-list-item" ui-sref-active="active" ui-sref="create-motion" ng-click="$ctrl.closeSidenav('left')">

                      <p class="md-body-1 ellipsis">{{'CREATE_NEW_MOTION' | translate | capitalize}}</p>
                      <md-icon class="mdi mdi-plus" aria-label="Create Motion"  layout-margin></md-icon>
                  </md-list-item>
              </md-list>
            </div>
        </md-content>
      `
    });

  MotionSidebarController.$inject = ['$timeout','$mdSidenav','MotionIndex','MotionSearch', 'Settings'];

	function MotionSidebarController($timeout, $mdSidenav, MotionIndex, MotionSearch, Settings) {

    /** global context for this */
		var self = this;

    /** @type {exports} */
    self.closeSidenav = closeSidenav;
    self.loadMotions = loadMotions;
		self.motionIndex = MotionIndex;
		self.search = MotionSearch;
    self.showVoteView = Settings.get('voting.on');

    /**
     * Pull to fill sidebar using MotionIndex service.
     * @return {}
     */
    function loadMotions() {
      if (Object.keys(self.motionIndex._index).length === 0) {
        return self.motionIndex._load();
      } else {
        return self.motionIndex.loadMoreMotions();
      }
    }

    /**
     * Close sidenav.
     * @param  {string} id $mdSidenav identifier
     * @return {}
     */
		function closeSidenav(id) {
		  $mdSidenav(id).close();
		}
	}
})();