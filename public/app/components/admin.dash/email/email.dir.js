'use strict';
(function(window, angular, undefined) {

  angular
    .module('iserveu')
    .directive('emailManager', [
      'settings',
      emailManager]);

  function emailManager(settings) {

    function emailManagerController() {
      this.service = settings;
      this.settings = settings.getData();
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
