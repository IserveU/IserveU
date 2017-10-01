(function() {

  'use strict';

  angular
    .module('app.error-page')
    .run(errorPageRunBlock);

  errorPageRunBlock.$inject = ['Router'];

  function errorPageRunBlock(Router) {

    Router.state('error', {
      url: '/error/:message',
      component: 'errorPageComponent',
      // TODO: declare options
      resolve: {
        message: ['$transition$', function($transition$) {
          console.log($transition$.params());
          return $transition$.params().message;
        }]
      }
    });

  }

})();