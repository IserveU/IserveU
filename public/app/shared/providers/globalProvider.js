/**
 * @description
 * @name isuApiProvider
 */

'use strict';
(function(window, angular, undefined) {
  angular
    .module('iserveu')
    .provider('$globalProvider', [
      'SETTINGS_JSON', function(SETTINGS_JSON) {

        this.$get = ['$injector', '$http', function($injector, $http) {
          return {
            /**
            * Initializes global variables.
            *
            */
            init: function() {
              var $rootScope = $injector.get('$rootScope');
              $rootScope.userIsLoggedIn = false;
              $rootScope.authenticatedUser = null;
              $rootScope.settingsGlobal = SETTINGS_JSON;

              // how to do this properly
              $rootScope.theme = SETTINGS_JSON.theme;
              $rootScope.theme.primary = 'primary';
              $rootScope.theme.accent = 'accent';
            },


            checkLogin: function(ev, requireLogin) {
              var $rootScope = $injector.get('$rootScope');
              var $state = $injector.get('$state');

              // this is a string in the settings.json
              if ($rootScope.settingsGlobal.authentication.required === '0'){
                return true;

              } else if (!$rootScope.userIsLoggedIn && requireLogin) {
                ev.preventDefault();
                $state.go('login');
              }

            },

            /**
             * Checks that the state is not a part of the motion module
             * and that the settings has it enabled. If the site has
             * the motion core disabled, it should not be able to
             * access this state.
             */
            checkMotion: function(ev, moduleMotion) {

              if (!moduleMotion) {
                return true;
              }

              if (!SETTINGS_JSON.motion.on && moduleMotion) {
                ev.preventDefault();
              }

            },

            /**
            * Checks that the user's credentials are in the local storage.
            * Assigns global variables that are checked in the view model
            * throughout the app.
            */
            checkUser: function() {
              var $rootScope = $injector.get('$rootScope');
              var incompleteProfile = $injector.get('incompleteProfileService');

              try {
                var user = JSON.parse(localStorage.getItem('user'));
              } catch (e) {
                 // alert('invalid json');
              }

              if (user && !angular.isUndefined(user)) {
                $rootScope.authenticatedUser = user;
                $rootScope.userIsLoggedIn = true;
                $rootScope.incompleteProfile = incompleteProfile.check(user);
              }
            },


            /**
            * Checks state permissions against the user's permissions that have
            * been defined on login. Redirects them to the home page if
            *   they do not have all the required permissions.
            */
            checkPermissions: function(ev, requirePermissions) {

              if (!requirePermissions) {
                return true;
              }

              var $state = $injector.get('$state');
              var $rootScope = $injector.get('$rootScope');
              var Authorizer = $injector.get('Authorizer');
              var authorized = Authorizer.canAccess(requirePermissions);

              if (authorized === false) {
                ev.preventDefault();
                $state.go('home');
                $rootScope.pageLoading = false;
              }
            },

            /**
            * Points current state name to a rootScope variable that is
            * accessed throughout the app for the sidebar directive
            * which dynamically renders the state's sidebar.
            */
            setState: function(state) {
              var $rootScope = $injector.get('$rootScope');
              $rootScope.currentState = state.name;
            }
          };
        }];
      }]);

}(window, window.angular));
