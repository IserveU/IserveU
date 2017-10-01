(function() {

  'use strict';

  angular
    .module('app.login')
    .run(loginRun);

  loginRun.$inject = ['Router'];

  function loginRun(Router) {

    Router
    .state('login', {
      url: '/login',
      component: 'loginComponent',
      data: {
        requireLogin: false
      },
      onEnter: ['$rootScope', function($rootScope) {
        $rootScope.isLoginState = true;
      }],
      onExit: ['$rootScope', function($rootScope) {
        $rootScope.isLoginState = false;
      }]
    })
    .state('reset-password', {
      url: '/reset-password',
      component: 'resetPasswordComponent',
      params: {
        requireLogin: true
      }
    })
    .state('reset-password.token', {
      url: '/:token',
      params: {
        requireLogin: false
      }
    })
  }
})();
