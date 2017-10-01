(function() {

  'use strict';

  angular
    .module('app.home')
    .run(homeRun);

  homeRun.$inject = ['Router'];

  function homeRun(Router) {

    Router.state('home', {
      url: '/home',
      component: 'homeComponent',
      data: {
        requireLogin: false
      },
      resolve: {
        home: ['Page', function(Page) {
          return Page.getIndex();
        }]
      }
    });

  }

})();