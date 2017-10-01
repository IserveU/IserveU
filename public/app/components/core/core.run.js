(function() {
    'use strict';

    angular
      .module('app.core')
      .run(runBlock);
    runBlock.$inject = ['$window', '$rootScope', '$transitions', '$location', 'LocalStorageManager', 'Settings'];


    function runBlock($window, $rootScope, $transitions, $location, LocalStorageManager, Settings) {

      (function activateRootscopeValues() {
        $rootScope.userIsLoggedIn = false;
        $rootScope.authenticatedUser = null;
      })();

      $transitions.onStart({}, function(trans) {

        /** Google Analytics */
        $window.ga('send', 'pageview', $location.path());

        /** Disable or enable state modules that are a part of motions. */
        if (trans.to().data.moduleMotion && !Settings.get('motion.on')) {
          trans.abort();
        }
      });

      /* Runs on page refreshes too */
      $window.onbeforeunload = function(e) {
        if (!LocalStorageManager.get('remember_me')) {
          return LocalStorageManager.logout();
        }
      };
    }

})();