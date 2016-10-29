(function() {

  'use strict';

  angular
    .module('iserveu')
    .directive('logoPng', ['pageService', 'utils', logoPng])
    .directive('logoAllWhitePng', ['pageService', 'utils', logoAllWhitePng])
    .directive('symbolPng', ['pageService', 'utils', symbolPng])
    .directive('symbolAllWhitePng', ['pageService', 'utils', symbolAllWhitePng]);

  function logoPng(pageService, utils) {
    return {
      restrict: 'EA',
      link: function (scope, el, attrs) {
        scope.home = pageService.index;
        utils.waitUntil(function() {
          return pageService.index;
        }, function() {
          scope.home = pageService.index[0];
        });
      },
      template: '<img class="logo" ng-src="/api/page/{{ home.slug }}/file/logo-png/resize/1920"/>'
    };
  }

  function logoAllWhitePng(pageService, utils) {
    return {
      restrict: 'EA',
      link: function (scope, el, attrs) {
        scope.home = pageService.index;
        utils.waitUntil(function() {
          return pageService.index;
        }, function() {
          scope.home = pageService.index[0];
        });
      },
      template: '<img class="logo" ng-src="/api/page/{{ home.slug }}/file/logo-allwhite-png/resize/1920"/>'
    };
  }

  function symbolPng(pageService, utils) {
    return {
      restrict: 'EA',
      link: function(scope, el, attrs) {
        scope.home = pageService.index;
        utils.waitUntil(function() {
          return pageService.index;
        }, function() {
          scope.home = pageService.index[0];
        });
      },
      template: '<img class="logo" ng-src="/api/page/{{ home.slug }}/file/symbol-png/resize/1920"/>'
    };
  }

  function symbolAllWhitePng(pageService, utils) {

    return {
      restrict: 'EA',
      link: function(scope, el, attrs) {
        scope.home = pageService.index;
        utils.waitUntil(function() {
          return pageService.index;
        }, function() {
          scope.home = pageService.index[0];
        });
      },
      template: '<img class="logo" ng-src="/api/page/{{ home.slug }}/file/symbol-allwhite-png/resize/1920"/>'
    };
  }

})();