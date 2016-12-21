'use strict';
(function(window, angular, undefined) {
  angular
    .module('iserveu')
    .config(['markedProvider', function (markedProvider) {
      markedProvider.setOptions({
        sanitize: true
      });
    }]);

  })(window, window.angular);
