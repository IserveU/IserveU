'use strict';
(function(window, angular, undefined) {


  angular
    .module('iserveu')
    .directive('pressEnter', pressEnter);

  function pressEnter() {

    return function(scope, element, attrs) {
      element.bind('keydown keypress', function(ev) {
        if (ev.which === 13) {
          scope.$apply(function() {
            scope.$eval(attrs.pressEnter);
          });
          ev.preventDefault();
        }
      });
    };

  }
}(window, window.angular));
