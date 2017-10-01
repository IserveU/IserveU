(function(window, angular, undefined) {

  'use strict';

  angular
    .module('app.settings')
    .run(runBlock);
  runBlock.$inject = ['$http', 'Settings', 'SETTINGS_INIT'];

  function runBlock($http, Settings, SETTINGS_INIT) {
    
    Settings.set(SETTINGS_INIT);


  }

})(window, window.angular);