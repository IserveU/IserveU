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
      this.showBetaMessage = false;
      this.saveAll = function(type){
        settings.saveTypeOf(type);
      }
      this.save = function(type,key,value) {
        var data = new Array();
        data[key.toString()] = value;
        settings.saveTypeOf(type,data);
      };

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
      this.toggleBetaMessage = function() {
        this.showBetaMessage = !this.showBetaMessage;
      };
    }

    return {
      restrict: 'EA',
      controller: systemManagerController,
      controllerAs: 'systemManager',
      templateUrl: 'app/components/admin.dash/system/system-manager.tpl.html'
    }

  }

})();
