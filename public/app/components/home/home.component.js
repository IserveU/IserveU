(function(window, angular, undefined) {
  
  'use strict';

	angular
		.module('app.home')
		.component('homeComponent', {

      controller: HomeComponentController,
      bindings: {
        home: '<'
      },
      template: `
      <page class="home layout-row layout-wrap flex" ng-cloak>
        <div id="centered_content">
          <introduction-component home-data="$ctrl.home.data" class="widget"></introduction-component>
          <my-votes-component class="widget" ng-if="$root.userIsLoggedIn && $root.authenticatedUser && $ctrl.showVoteView"></my-votes-component>
          <top-motions-component class="widget" ng-if="$ctrl.showMotionView && $ctrl.showTopMotionWidget"></top-motions-component>
          <my-comments-component class="widget" ng-if="$root.userIsLoggedIn && $root.authenticatedUser && $ctrl.showCommentView"></my-comments-component>  
          <top-comments-component class="widget" ng-if="$ctrl.showCommentView"></top-comments-component>
        </div>

        <floating-button md-direction="left" has-permission="administrate-motion" class="motion_fab"
          init-buttons="['edit']"
          on-edit="$ctrl.edit($ctrl.home.data[0].slug)">
        </floating-button>
      </page>`
  });

  HomeComponentController.$inject = ['$state', 'Settings'];

  function HomeComponentController($state, Settings) {

    this.edit = function(home) {
      $state.go('edit-page', {id: home});
    };

    this.showVoteView = Settings.get('voting.on');
    this.showMotionView = Settings.get('motion.on');
    this.showCommentView = Settings.get('comment.on');
    this.showTopMotionWidget = Settings.get('home.widgets.top_motions');
  }


})(window, window.angular);