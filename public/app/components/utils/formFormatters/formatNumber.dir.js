'use strict';
(function(window, angular, undefined) {

  angular
    .module('app.utils')
    .directive('formatNumber', ['$filter', formatNumber]);

  function formatNumber($filter) {

    return {
      require: 'ngModel',
      link: function(scope, element, attrs, ngModelController) {

        ngModelController.$parsers.push(function(data) {
          return +data;
        });

        ngModelController.$formatters.push(function(data) {
          return +data;
        });
      }
    };
  }

}(window, window.angular));
