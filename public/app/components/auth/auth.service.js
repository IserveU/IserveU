(function() {

  'use strict';

  angular
    .module('app.auth')
    .service('Auth', Auth);

  Auth.$inject = [
    '$rootScope', 
    '$timeout', 
    'LocalStorageManager', 
    'AuthResource', 
    'Settings', 
    'Redirect', 
    'MotionIndex', 
    'Utils'
  ];

  function Auth($rootScope, $timeout, LocalStorageManager, AuthResource, Settings, Redirect, MotionIndex, Utils) {

    var service = {
      logout: logout,
      checkForAuthenticatedUser: checkForAuthenticatedUser,
      handleAuthentication: handleAuthentication,
      clearCredentials: clearCredentials,
      isAuthenticated: isAuthenticated
    };

    return service;

/** ====================== Export Functions ======================================*/

    function logout() {
      service.clearCredentials();
      MotionIndex.clear();
      Redirect.redirectOnLogout();
    }

    function checkForAuthenticatedUser() {
      try {
        var user = JSON.parse(localStorage.getItem('user'));
      } catch (e) {
         console.log('invalid json in local storage');
      }

      if (user && !angular.isUndefined(user)) {
        $rootScope.authenticatedUser = user;
        $rootScope.userIsLoggedIn = true;
        // TODO: $rootScope.incompleteProfile = incompleteProfile.check(user);
      }
    }

    function handleAuthentication(response) {

      LocalStorageManager.remove('agreement_accepted');

      const user = response.user || response.data || response;
      const permissions = Utils.transformObjectToArray(user.permissions);

      $rootScope.userIsLoggedIn = true;
      $rootScope.authenticatedUser = user;
      $rootScope.authenticatedUser.permissions = permissions;

      MotionIndex.clear();

      LocalStorageManager.setCredentials(user);

      // Temporary fix to the user agreement not having a service and directive
      if (!user.agreement_accepted) {
        window.location.href = "/";
      }

      $timeout(function() {
        Redirect.redirect()
      }, 250);
    }

    function clearCredentials() {

      $rootScope.authenticatedUser = null;
      $rootScope.userIsLoggedIn = false;
      LocalStorageManager.clearCredentials();

    /** Make as a chainable method */
      return this;
    }

    function isAuthenticated(requireLogin) {
      var authRequired = parseInt(Settings.get('authentication.required')); // string value 0 or 1

      // Every state is accessible when authentication is not required.
      if (!authRequired || !requireLogin)
        return true
      else
        return ($rootScope.userIsLoggedIn && $rootScope.authenticatedUser)
    }

  }

})();