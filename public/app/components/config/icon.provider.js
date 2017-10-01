'use strict';
(function(window, angular, undefined) {

  angular
    .module('app.config')
    .config(['$mdIconProvider',
    function($mdIconProvider) {


      $mdIconProvider.icon('loading', '/symbols/loading.svg');
      $mdIconProvider.icon('thumb-down-outline', '/symbols/thumb-down-outline.svg');
      $mdIconProvider.icon('thumb-down', '/symbols/thumb-down.svg');
      $mdIconProvider.icon('thumb-up-outline', '/symbols/thumb-up-outline.svg');
      $mdIconProvider.icon('thumb-up', '/symbols/thumb-up.svg');
      $mdIconProvider.icon('thumbs-up-down-outline', '/symbols/thumbs-up-down-outline.svg');
      $mdIconProvider.icon('thumbs-up-down', '/symbols/thumbs-up-down.svg');
      $mdIconProvider.icon('calendar-remove', '/symbols/calendar-remove.svg');
      $mdIconProvider.icon('calendar-clock', '/symbols/calendar-clock.svg');





  }])
})(window, window.angular);