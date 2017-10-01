'use strict';
(function(window, angular, undefined) {

  angular
    .module('app.utils')
    .directive('focusInput', ['$timeout', focusInput]);

  function focusInput($timeout) {

    return {
      restrict: 'A',
      link: function($scope, $element, $attr) {
        $scope.$watch($attr.focusInput, function(_focusVal) {
          $timeout(function() {
            if (_focusVal) {
              $element.focus();
            } else {
              $element.blur();
            }
          });
        });
      }
    };


  }

})(window, window.angular);
