(function() {

  'use strict';

  angular
    .module('iserveu')
    .directive('systemManager', ['settings', 'departmentManagerService', systemManager]);

  function systemManager(settings, departmentManagerService) {

    function systemManagerController() {

      this.department = departmentManagerService;
      this.service = settings;
      this.showDepartment = false;
      this.showMotion = false;
      this.showSecurity = false;
      this.showVoting = false;

      this.toggleDepartment = function() {
        this.showDepartment = !this.showDepartment;
      };

      this.toggleMotion = function() {
        this.showMotion = !this.showMotion;
      };

      this.toggleSecurity = function() {
        this.showSecurity = !this.showSecurity;
      };

      this.toggleVoting = function() {
        this.showVoting = !this.showVoting;
      };

    }

    function systemManagerLinkMethod(scope, el, attrs) {

    }

    return {
      restrict: 'EA',
      link: systemManagerLinkMethod,
      controller: systemManagerController,
      controllerAs: 'systemManager',
      templateUrl: 'app/components/admin.dash/system/system-manager.tpl.html'
    }

  }

})();