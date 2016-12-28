'use strict';
(function(window, angular, undefined) {

  angular
    .module('iserveu')
    .directive('floatingButton', ['$window', 'utils', floatingButton]);

  function floatingButton($window, utils) {

    function floatingButtonController() {
      this.isOpen = false;
      // this.topDirections = ['left', 'up'];
      // this.bottomDirections = ['down', 'right'];
      this.show = {
        create: false,
        edit: false,
        delete: false
      };
    }

    function floatingButtonLink(scope, el, attrs, ctrl) {
      var buttons = angular.extend([], scope.$eval(attrs.initButtons));

      for (var i in ctrl.show) {
        if (buttons.includes(i)) {
          ctrl.show[i] = true;
        }
      }
    }

    return {
      controller: floatingButtonController,
      controllerAs: 'floatingButton',
      link: floatingButtonLink,
      bindToController: {
        onCreate: '&',
        onEdit: '&',
        onDelete: '&'
      },
      templateUrl: ['app/shared/directives/',
        'floatingButton/floatingButton.tpl.html'].join('')
    };
  }

})(window, window.angular);
