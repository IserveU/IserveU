'use strict';
(function(window, angular, undefined) {

  angular
    .module('app.widgets')
    .directive('floatingButton', floatingButton);

  function floatingButton() {

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
        if (buttons.indexOf(i) >= 0)
          ctrl.show[i] = true;
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
      templateUrl: 'app/components/widgets/floatingButton/floatingButton.tpl.html'
    };
  }

})(window, window.angular);
