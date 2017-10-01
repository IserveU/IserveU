'use strict';
(function(window, angular, undefined) {
  angular
    .module('app.config')
    .config(['markedProvider', function (markedProvider) {
      markedProvider.setOptions({
        sanitize: true
      });
    }]);

  })(window, window.angular);
