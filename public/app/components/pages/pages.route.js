(function() {

  'use strict';

  angular
    .module('app.pages')
    .run(pagesRun);

  pagesRun.$inject = ['Router'];

  function pagesRun(Router) {

    Router
      .state('pages', {
        url: '/pages/:id',
        component: 'pageComponent',
        data: {
          requireLogin: true
        }
      })
      .state('edit-page', {
        url: '^/page/:id/edit',
        component: 'editPageComponent',
        params: {
          requireLogin: true,
          requirePermissions: ['administrate-motion']
        }
      });
  
  }
})();
