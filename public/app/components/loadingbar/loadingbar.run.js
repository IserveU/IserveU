(function() {
    'use strict';

    angular
        .module('app.loadingbar')
        .run(loadingbarRunBlock);

    loadingbarRunBlock.$inject = ['$rootScope', 'cfpLoadingBar'];

    function loadingbarRunBlock($rootScope, cfpLoadingBar) {

      $rootScope.$on('cfpLoadingBar:loading', function() {
        $rootScope.pageLoading = true;
      });

      $rootScope.$on('cfpLoadingBar:completed', function() {
        $rootScope.pageLoading = false;
      });

      // Loading bar transition
      // -----------------------------------
      // var thBar;
      // $rootScope.$on('$stateChangeStart', function() {
      //     thBar = $timeout(function() {
      //         cfpLoadingBar.start();
      //     }, 0); // sets a latency Threshold
      // });
      // $rootScope.$on('$stateChangeSuccess', function(event) {
      //     event.targetScope.$watch('$viewContentLoaded', function() {
      //         $timeout.cancel(thBar);
      //         cfpLoadingBar.complete();
      //     });
      // });

    }

})();
