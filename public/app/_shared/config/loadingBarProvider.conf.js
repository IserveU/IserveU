'use strict';
(function(window, angular, undefined) {

  angular
    .module('iserveu')
    .config(['cfpLoadingBarProvider',

  function(cfpLoadingBarProvider) {

    cfpLoadingBarProvider.includeSpinner = false;
    cfpLoadingBarProvider.latencyThreshold = 500;

  }]);
})(window, window.angular);
