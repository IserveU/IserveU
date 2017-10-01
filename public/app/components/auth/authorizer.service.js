'use strict';
(function (window, angular, undefined) {
  angular
    .module('app.auth')
    .service('Authorizer', [
      '$rootScope',
      Authorizer])
/**
* http://adamalbrecht.com/2014/09/22/authorization-with-angular-and-ui-router/
*/
  function Authorizer ($rootScope) {
    this.canAccess = function (requirePermissions) {
      if (angular.isUndefined(requirePermissions)) { return false }

      var user = $rootScope.authenticatedUser

      if (!user) { return false }

      if (!angular.isArray(requirePermissions)) {
        requirePermissions = [requirePermissions]
      }

      return requirePermissions.every(function (el) {
        var permission = el.trim()

        /* Negation */
        if (permission[0] === '!') {
          permission = permission.slice(1)
          return user.permissions.indexOf(permission) < 0
        }

        /* Regular */
        return user.permissions.indexOf(permission) >= 0
      })
    }

    /**
     * Converts a permission into the string in the permission file.
     * May belong in a 'translate' service if it exists
     * @param  {string} permission The permission code
     * @return {string}            The key for the tranlsate file
     */
    this.permissionToTranslateKey = function (permission) {
      permission = permission.replace('-', '').toUpperCase()

      return 'PERMISSION_' + permission
    }
  }
})(window, window.angular)
