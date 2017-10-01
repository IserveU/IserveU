'use strict';
(function(window, angular, undefined) {


  angular
    .module('app.utils')
    .directive('formatDate', ['$filter', formatDate]);

  function formatDate($filter) {

    return {
      require: 'ngModel',
      link: function(scope, element, attrs, ngModelController) {

        ngModelController.$parsers.push(function(data) {
          return $filter('date')(data, 'yyyy-MM-dd HH:mm:ss Z');
        });

        ngModelController.$formatters.push(function(data) {
          return data ? new Date(data) : data;
        });
      }
    };
  }

}(window, window.angular));
