'use strict';
(function(window, angular, undefined) {

  angular
    .module('app.admin.dash')
    .directive('emailManager', [
      'Settings',
      emailManager]);

  function emailManager(Settings) {

    function emailManagerController() {
      this.service = Settings;
      this.settings = Settings.getData();
      this.showWelcome = false;

      this.toggleWelcome = function() {
        this.showWelcome = !this.showWelcome;
      }

    }


    return {
      restrict: 'EA',
      controller: emailManagerController,
      controllerAs: 'emailManager',
      templateUrl: 'app/components/admin.dash/email/email.tpl.html'
    };

  }

})(window, window.angular);
