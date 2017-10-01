'use strict';
(function(window, angular, undefined) {

  angular
    .module('iserveu')
    .directive('isuAccordian', isuAccordian);

  function isuAccordian() {
    return {
      transclude: true,
      link: function(scope, el, attrs) {

        scope.expand = function(item) {
          item.open = !item.open;
        };

        scope.items = [
          {
            title: 'Dynamic Group Header - 1',
            content: 'Dynamic Group Body - 1',
            open: false
          },
          {
            title: 'Dynamic Group Header - 2',
            content: 'Dynamic Group Body - 2',
            open: false
          }
        ];
      },
      templateUrl: 'app/shared/directives/accordian/accordian.tpl.html'
    };
  }

})(window, window.angular);
