'use strict';
(function(window, angular, undefined) {

  angular
  .module('iserveu')
  .factory('settingsUtils', settingsUtils);

  function settingsUtils() {

    function getLogo() {

    }

    return {
      getLogo: getLogo
    }
  }
})(window, window.angular);