'use strict';
(function(window, angular, undefined) {

  angular
    .module('iserveu')
    .directive('motionDrafts', ['motionResource', motionDrafts]);

  function motionDrafts(motionResource) {

    function motionDraftController() {

      var self = this; // global context for 'this'

      motionResource.getDrafts(['draft', 'review']).then(function(r) {
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
