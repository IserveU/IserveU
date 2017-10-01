(function() {

  'use strict';

  angular
    .module('app.vote')
    .run(voteRun);

  voteRun.$inject = ['Router'];

  function voteRun(Router) {
    Router.state('vote-motion-url', {
      url: '/motion/:slug/vote/:position',
      template: '<email-vote></email-vote>',
      params: {
        requireLogin: true,
        moduleMotion: true
      }
    });
  }

})();
