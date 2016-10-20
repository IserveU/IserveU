'use strict';
(function(window, angular, undefined) {

  angular
    .module('iserveu')
    .directive('formatClosing', ['$filter', formatClosing]);

  function formatClosing($filter) {

    return {
      require: 'ngModel',
      link: function(scope, element, attrs, ngModelController) {

        ngModelController.$parsers.push(function(data) {
          return $filter('date')(data, 'yyyy-MM-dd HH:mm:ss');
        });

        ngModelController.$formatters.push(function(data) {
          var date = data.carbon ? new Date(data.carbon.date) : new Date(data);
          return date;
        });
      }
    };
  }

}(window, window.angular));
