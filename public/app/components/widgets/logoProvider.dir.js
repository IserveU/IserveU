(function() {

  'use strict';

  angular
    .module('app.widgets')
    .directive('logoPng', ['Page', 'Utils', logoPng])
    .directive('logoAllWhitePng', ['Page', 'Utils', logoAllWhitePng])
    .directive('symbolPng', ['Page', 'Utils', symbolPng])
    .directive('symbolAllWhitePng', ['Page', 'Utils', symbolAllWhitePng]);

  function logoPng(pageService, Utils) {
    return {
      restrict: 'EA',
      scope: {
        resizeWidth: '='
      },
      link: function (scope, el, attrs) {
        scope.home = pageService.index;
        Utils.waitUntil(function() {
          return pageService.index;
        }, function() {
          scope.home = pageService.index[0];
        });
      },
      template: '<img class="logo" ng-src="/api/page/1/file/logo-png/resize/{{ resizeWidth || \'1920\' }}"/>'
    };
  }

  function logoAllWhitePng(pageService, Utils) {
    return {
      restrict: 'EA',
      scope: {
        resizeWidth: '='
      },
      link: function (scope, el, attrs) {
        scope.home = pageService.index;
        Utils.waitUntil(function() {
          return pageService.index;
        }, function() {
          scope.home = pageService.index[0];
        });
      },
      template: '<img class="logo" ng-src="/api/page/1/file/logo-allwhite-png/resize/{{ resizeWidth || \'1920\' }}"/>'
    };
  }

  function symbolPng(pageService, Utils) {
    return {
      restrict: 'EA',
      scope: {
        resizeWidth: '='
      },
      link: function(scope, el, attrs) {
        scope.home = pageService.index;
        Utils.waitUntil(function() {
          return pageService.index;
        }, function() {
          scope.home = pageService.index[0];
        });
      },
      template: '<img ng-src="/api/page/1/file/symbol-png/resize/{{ resizeWidth || \'1920\' }}"/>'
    };
  }

  function symbolAllWhitePng(pageService, Utils) {

    return {
      restrict: 'EA',
      scope: {
        resizeWidth: '='
      },
      link: function(scope, el, attrs) {
        scope.home = pageService.index;
        Utils.waitUntil(function() {
          return pageService.index;
        }, function() {
          scope.home = pageService.index[0];
        });
      },
      template: '<img ng-src="/api/page/1/file/symbol-allwhite-png/resize/{{ resizeWidth || \'1920\' }}"/>'
    };
  }

})();