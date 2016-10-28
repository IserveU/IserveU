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
              $rootScope.theme.name = SETTINGS_JSON.theme.name;
              $rootScope.theme.primary = angular.isObject(SETTINGS_JSON.theme.colors.primary) ? 'primary' : SETTINGS_JSON.theme.colors.primary;
              $rootScope.theme.accent = angular.isObject(SETTINGS_JSON.theme.colors.accent) ? 'accent' : SETTINGS_JSON.theme.colors.accent;

            },

            /**
            * Global file drop uploader to textAngular.
            *
            */
            dropHandler: function(file, insertAction) {
              var fileService = $injector.get('fileService');
              var reader = new FileReader();
              if (file.type.substring(0, 5) === 'image') {
                reader.onload = function() {
                  if (reader.result !== '')
                    fileService.upload(file).then(function(r) {
                      insertAction('insertImage',
                        '/uploads/' + r.data.filename, true);
                    }, function(e) { console.log(e); });
                };

                reader.readAsDataURL(file);
                return true;
              }
              return false;
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
              $rootScope.isLoginState = state.name.substr(0, 5) === 'login';
            }
          };
        }];
      }]);

}(window, window.angular));
