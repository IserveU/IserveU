(function() {

  'use strict';

  angular
    .module('app.router')
    .provider('Router', RouterProvider);
  RouterProvider.$inject = ['$locationProvider', '$stateProvider', '$urlServiceProvider'];

  function RouterProvider($locationProvider, $stateProvider, $urlServiceProvider) {
    var config = {
      defaultRoute: '/home',
      html5Mode: false
    };

    $locationProvider.html5Mode(config.html5Mode);

    /** ui-router rules for state changes */    
    $urlServiceProvider.rules.otherwise(config.defaultRoute);
    $urlServiceProvider.rules.when('/user/:id', '/user/:id/profile');

    /** When Router as a Service is invoked */
    this.$get = Router;

    Router.$inject = ['$rootScope', '$transitions', '$window', '$location']; // add in Auth
    function Router ($rootScope, $transitions, $window, $location) {
      var service = {
        state: state,
        getStates: getStates
      };

      init();

      return service;

/**==== Router functions to export below ===================================*/
      function state(name, options) {
        if (!name) throw new Error('Route name not defined.');

        $stateProvider.state(name, options);

        // allow chain execution
        return this;
      }
      
      function getStates() {
        return $transitions.get();
      }

      function init() {
        $transitions.onStart({}, function(trans) {
          $window.ga('send', 'pageview', $location.path());

          console.log('from: ', trans.from());
          console.log('to: ', trans.to());

          trans.promise.finally(function() {
            console.log('ending');
          });
        });
      }

      function scrollTopMainView() {
        var main = document.querySelector('main');
        if (main) main.scrollTop = 0;
      }

    }

  }

})();