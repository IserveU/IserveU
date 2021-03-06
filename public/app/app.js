'use strict';
(function(window, angular, undefined) {

  var iserveu = angular.module('iserveu', [
    'ngCookies',
    'ngResource',
    'ngMaterial',
    'ngMessages',
    'ngSanitize',
    'satellizer',
    'ui.router',
    'flow',
    'infinite-scroll',
    'pascalprecht.translate',
    'mdColorPicker',
    'isu-form-sections',
    'angular-loading-bar',
    'alloyeditor',
    'hc.marked'
  ]).run(['$rootScope', '$window', '$location', '$timeout', '$globalProvider', '$stateParams',
    '$state', '$mdDialog', 'motionResource','localStorageManager',
    function($rootScope, $window, $location, $timeout, $globalProvider, $stateParams,
      $state, $mdDialog, motionResource, localStorageManager) {

      $rootScope.preventStateChange = false;

      $rootScope.$on('$stateChangeSuccess', function(event) {
        $window.ga('send', 'pageview', $location.path());
      });

      $rootScope.$on('$stateChangeStart', function(event, toState,
        toParams, fromState, fromParams) {

        if (!$rootScope.preventStateChange &&
          fromState.name === 'create-motion'
          && $stateParams.id) {

          event.preventDefault();
          var confirm = $mdDialog.confirm()
                .parent(angular.element(document.body))
                .title('Would you like to discard this draft?')
                .textContent('Your changes and draft will not be saved.')
                .ariaLabel('Navigate away from create-motion')
                .ok('Please do it!')
                .cancel('No thanks.');

          $mdDialog.show(confirm).then(function() {
            motionResource.deleteMotion($stateParams.id);
            $stateParams.id = null;
            $state.go(toState.name, {'id': toParams.id} || toState.name || 'home');
          }, function() {});

        } else if (fromState.name === 'create-page' && $stateParams.id) {
          event.preventDefault();

          var confirm2 = $mdDialog.confirm()
            .parent(angular.element(document.body))
            .title('Would you like to discard this draft?')
            .textContent('Your changes and draft will not be saved.')
            .ariaLabel('Navigate away from create-motion')
            .ok('Please do it!')
            .cancel('No thanks.');

          $mdDialog.show(confirm2).then(function() {
            // pageService.destroy($stateParams.id);
            $stateParams.id = null;
            $state.go(toState.name, {'id': toParams.id} || toState.name || 'home');
          }, function() {});
        } else {
          $globalProvider.checkUser();
          $globalProvider.checkLogin(event, toState.data.requireLogin);
          $globalProvider.checkMotion(event, toState.data.moduleMotion);
          $globalProvider.checkPermissions(event, toState.data.requirePermissions);
          $globalProvider.setState(toState);
        }
      });

      $rootScope.$on('cfpLoadingBar:loading', function() {
        $rootScope.pageLoading = true;
      });

      $rootScope.$on('cfpLoadingBar:completed', function() {
        $rootScope.pageLoading = false;
      });

      $globalProvider.init();

      /* Runs on page refreshes too */
      $window.onbeforeunload = function(e) {
        
        if (!localStorageManager.get('remember_me')){
          return localStorageManager.logout();
        }
          
      };
    }]);

  fetchData().then(bootstrapApplication);

  function fetchData() {
    var initInjector = angular.injector(['ng']);
    var $http = initInjector.get('$http');
    var $window = initInjector.get('$window');

    return $http.get('/api/setting').then(function(response) {

      var settings = response.data;

      // Google analytics
      $window.ga('create', settings.analytics_id, 'auto');

      iserveu.constant('SETTINGS_JSON', settings);

      document.body.style.backgroundImage = (('url(' + settings.theme.background + ')')
        || '#FBFBFB');

    }, function(errorResponse) {
    });

  }

  function bootstrapApplication() {
    angular.element(document).ready(function() {
      
      angular.bootstrap(document, ['iserveu'], {strictDi: true});
    });
  }

}(window, window.angular));
