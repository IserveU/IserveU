'use strict';
(function(window, angular, undefined) {

  angular
    .module('app.admin.dash')
    .directive('motionDrafts', ['MotionResource', motionDrafts]);

  function motionDrafts(MotionResource) {

    function motionDraftController() {

      var self = this; // global context for 'this'

      MotionResource.getDrafts(['draft', 'review']).then(function(r) {
        self.motions = r.data.data;
      });

    }

    return {
      controller: motionDraftController,
      controllerAs: 'draft',
      templateUrl: 'app/components/admin.dash/drafts/motion-drafts.tpl.html'
    };

  }

})(window, window.angular);
