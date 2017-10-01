'use strict';
(function (window, angular, undefined) {
  angular
    .module('iserveu')
    .directive('hasPermission', ['Authorizer', hasPermission])

  function hasPermission (Authorizer) {
    return {
      restrict: 'AE',
      link: function (scope, el, attrs) {
        attrs.$observe('hasPermission', function (value) {
          var permissions = value.split(',')

          if (!Authorizer.canAccess(permissions)) {
            el.remove(attrs.hasPermission)
          }
        })
      }
    }
  }
}(window, window.angular))
