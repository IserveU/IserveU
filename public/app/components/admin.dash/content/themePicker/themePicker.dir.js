'use strict';
(function(window, angular, undefined) {

angular
  .module('iserveu')
  .controller('ThemeDemoCtrl', ['$scope', '$mdColorPalette', function ($scope, $mdColorPalette) {
    $scope.colors = Object.keys($mdColorPalette);

    $scope.primary = 'purple';
    $scope.accent = 'green';

    $scope.isPrimary = true;

    $scope.selectTheme = function (color) {
      if ($scope.isPrimary) {
        $scope.primary = color;

        $scope.isPrimary = false;
      }
      else {
        $scope.accent = color;

        $scope.isPrimary = true;
      }
    };
  }])
  .directive('themePreview', function () {
    return {
      restrict: 'E',
      templateUrl: 'app/components/admin.dash/content/themePicker/themePreview.tpl.html',
      scope: {
        primary: '=',
        accent: '='
      },
      controller: ['$scope', '$mdColors', '$mdColorUtil',
        function ($scope, $mdColors, $mdColorUtil) {
          $scope.getColor = function (color) {
            return $mdColorUtil.rgbaToHex($mdColors.getThemeColor(color))
          };
        }
      ]}
  })
  .directive('mdJustified', function() {
    return {
      restrict : 'A',
      compile : function(element, attrs)  {
        var layoutDirection = 'layout-'+ (attrs.mdJustified || "row");

        element.removeAttr('md-justified');
        element.addClass(layoutDirection);
        element.addClass("layout-align-space-between-stretch");

        return angular.noop;
      }
    };
  });
})(window, window.angular);