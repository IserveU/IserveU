(function(window, angular, undefined) {

    'use strict';

    angular.module('iserveu', [

      // Functional Components
      'app.core',
      'app.config',
      'app.header',
      'app.loadingbar',
      'app.settings',
      'app.sidebar',
      'app.utils',
      'app.widgets',

      // App Components
      'app.admin.dash',
      'app.api',
      'app.auth',
      'app.comment',
      'app.error-page',
      'app.footer',
      'app.home',
      'app.login',
      'app.motions',
      // 'app.notifications', not yet integrated
      'app.pages',
      'app.vote', 
      'app.user'
    ]);

/**=============== Manual boostrap to load configuration =====*/

  fetchData().then(bootstrap);

/**===========================================================*/

  function fetchData() {
    var injector = angular.injector(['ng']);
    var $http    = injector.get('$http');
    var $window  = injector.get('$window');

    return $http.get('/api/setting').then(function(response) {

      var settings = response.data;

      // Google analytics initialization.
      $window
        .ga('create', settings.analytics_id, 'auto');

      // Application-wide constant.
      angular
        .module('iserveu')
        .constant('SETTINGS_INIT', settings);

      // Conditional to assign background image or keep as white.
      document.body.style.backgroundImage = (('url(' + settings.theme.background + ')')
        || '#FBFBFB');

    }, function(error) {
      console.error(error);
      console.error('TODO: give some app response that something is broken, in better html');
      document.write('Application is not working.');
    });
  }

  function bootstrap() {
    angular.element(document).ready(function() {
      angular.bootstrap(document, ['iserveu'], {strictDi: true});
    });
  }

})(window, window.angular);

