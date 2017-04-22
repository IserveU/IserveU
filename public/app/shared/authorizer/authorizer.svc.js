'use strict';
(function(window, angular, undefined) {

  angular
    .module('iserveu')
    .service('Authorizer', [
      '$rootScope',
      Authorizer]);
/**
* http://adamalbrecht.com/2014/09/22/authorization-with-angular-and-ui-router/
*/
  function Authorizer($rootScope) {

    this.canAccess = function(requirePermissions) {

      var user = $rootScope.authenticatedUser;

      if (!user || angular.isUndefined(requirePermissions))
        return false;

      if (!angular.isArray(requirePermissions)) {
        requirePermissions = [requirePermissions];
      }

      return requirePermissions.every(function(el) {
        return user.permissions.indexOf(el.trim()) >= 0;
      });
    };
    
    /**
     * Converts a permission into the string in the permission file.
     * May belong in a 'translate' service if it exists
     * @param  {string} permission The permission code
     * @return {string}            The key for the tranlsate file
     */
    this.permissionToTranslateKey = function(permission){
      permission = permission.replace('-','').toUpperCase();
      
      return "PERMISSION_"+permission;
    }

  }
})(window, window.angular);
