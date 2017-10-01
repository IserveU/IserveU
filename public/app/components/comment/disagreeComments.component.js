(function() {
  
  'use strict';

  angular
    .module('app.comment')
    .directive('disagreeCommentsComponent', {
      require: { parent: '^^commentListComponent' },
      template: `
      <md-list ng-show="$ctrl.parent.motion.motionComments.disagreeComments.length != 0" class="motion_commentlist">
        <md-list-item class="md-3-line md-long-text motion_commentlist__item" ng-repeat="comment in $ctrl.parent.motion.motionComments.disagreeComments | orderBy:'-commentRank'">
          <md-icon class="mdi md-avatar-icon mdi-account-circle"></md-icon>

              <div class="md-list-item-text ">
                  <h3 class="motion_commentlist__usertitle" ng-bind="comment.commentWriter.first_name ? comment.commentWriter.first_name + ' ' + comment.commentWriter.last_name : comment.commentWriter.community.adjective"></h3>
                  <h4 class="motion_commentlist__date" ng-bind="show.formatDate(comment)"></h4>
                  <div class="motion_commentlist__text" marked="comment.text"></div>
                  <comment-vote-component motion="$ctrl.parent.motion" position="-1" comment-id="comment.id"></comment-vote-component>
              </div>

          </md-list-item>
      </md-list>

      <section ng-show="$ctrl.parent.motion.motionComments.disagreeComments.length == 0">
        <md-icon class="mdi mdi-microphone-off title-icon"></md-icon>
        <h1 class="md-subhead title-header" translate="COMMENTLIST_NODISAGREE"></h1>
      </section>
    `
    });

})();