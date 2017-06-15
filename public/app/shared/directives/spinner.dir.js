'use strict';
(function (window, angular, undefined) {
  angular
    .module('iserveu')
    .directive('spinner', ['settings', '$timeout', spinner])

     /** @ngInject */
  function spinner (settings, $timeout) {
    // TODO: make loading circle changeable from settings array

    return {
      restrict: 'E',
      transclude: true,
      link: function (scope, el, attrs) {
        scope.onFinish = false

        function flipOnFinish () {
          scope.onFinish = !scope.onFinish
        }

        scope.$watch('onHide', function (oldValue, newValue) {
          if (!oldValue && newValue) {
            flipOnFinish()
            $timeout(function () {
              flipOnFinish()
              scope.onLoaded()
            }, 1400)
          }
        }, true)
      },
      scope: {
        'name': '=',
        'onLoaded': '&',
        'primaryAction': '&',
        'onHide': '=',
        'formName': '=',
        'secondaryBtn': '=',
        'tertiaryBtn': '='
      },
      template: [
        '<md-button ng-disabled="formName.$invalid" type="submit" ng-click="primaryAction()">',

        '<md-icon class="mdi mdi-check" style="margin-bottom: 14px; margin-right: 5px" ng-show="onFinish"></md-icon>',

        '<span ng-hide="onHide">{{name}}</span>',

        '<md-icon md-svg-src="loading" ng-show="onHide"></md-icon>',
        '</md-button>',
        '<md-button ng-if="secondaryBtn" ng-click="secondaryBtn.control()">',
        '{{secondaryBtn.name}}',
        '</md-button>',
        '<md-button ng-if="tertiaryBtn" ng-click="tertiaryBtn.control()">',
        '{{tertiaryBtn.name}}',
        '</md-button>'
      ].join('')
    }
  }
})(window, window.angular)
