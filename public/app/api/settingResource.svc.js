'use strict';
(function(window, angular, undefined) {

  angular
    .module('iserveu')
    .factory('settingResource', [
      '$resource',
      '$q',
      '$rootScope',
      'authResource',
      settingResource]);

  function settingResource($resource, $q, $rootScope, authResource) {

    var Setting = $resource('api/setting/:setting', {setting: '@setting'}, {
      'update': { method: 'PUT' }
    });

    /*****************************************************************
    *
    * Server-side functions.
    *
    ******************************************************************/

    function settingRequest(data) {
      return Setting.update(data)
    }

    return {
      settingRequest: settingRequest,
    };
  }
})(window, window.angular);