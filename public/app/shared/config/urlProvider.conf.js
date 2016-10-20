'use strict';
(function(window, angular, undefined) {

  angular
    .module('iserveu')
    .config([
      '$urlRouterProvider',
      '$authProvider',
      '$compileProvider',

      function($urlRouterProvider, $authProvider, $compileProvider) {

        // speeds up the app, the debug info are for {{}}
        $compileProvider.debugInfoEnabled(false);

        $authProvider.loginUrl = '/authenticate';

        // The overall default route for the app.
        // If no matching route is found, then go here
        $urlRouterProvider.otherwise('/home');
        // for displaying sub-url
        $urlRouterProvider.when('/user/:id', '/user/:id/profile');

      }]);

})(window, window.angular);
