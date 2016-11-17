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
    'alloyeditor'
  ])
  .run(['$rootScope', '$window', '$timeout', '$globalProvider', '$stateParams',
    '$state', '$mdDialog', 'motionResource',
    function($rootScope, $window, $timeout, $globalProvider, $stateParams,
      $state, $mdDialog, motionResource) {

      $rootScope.preventStateChange = false;

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
            $state.go(toState.name || 'home');
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
            $state.go(toState.name || 'home');
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

      $window.onbeforeunload = function(e) {
        var publicComputer = localStorage.getItem('public_computer');
        if (JSON.parse(publicComputer) === true)
          return localStorage.clear();
      };
    }]);

  fetchData().then(bootstrapApplication);

  function fetchData() {
    var initInjector = angular.injector(['ng']);
    var $http = initInjector.get('$http');

    return $http.get('/api/setting').then(function(response) {

      var settings = response.data;

      iserveu.constant('SETTINGS_JSON', settings);

      document.body.style.backgroundImage = (('url(' + settings.theme.background + ')')
        || '#FBFBFB');

    }, function(errorResponse) {
      console.log('error');
    });

  }

  function bootstrapApplication() {
    angular.element(document).ready(function() {
      angular.bootstrap(document, ['iserveu'], {strictDi: true});
    });
  }

}(window, window.angular));
