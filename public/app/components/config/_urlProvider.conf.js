'use strict';
(function(window, angular, undefined) {

  angular
    .module('iserveu')
    .config([
      '$urlServiceProvider',
      '$authProvider',
      '$compileProvider',

      function($urlServiceProvider, $authProvider, $compileProvider) {

        // speeds up the app, the debug info are for {{}}
        $compileProvider.debugInfoEnabled(false);

        $authProvider.loginUrl = '/authenticate';

        // The overall default route for the app.
        // If no matching route is found, then go here
        $urlServiceProvider.rules.otherwise('/home');
        // for displaying sub-url
        $urlServiceProvider.rules.when('/user/:id', '/user/:id/profile');

      }]);

})(window, window.angular);
