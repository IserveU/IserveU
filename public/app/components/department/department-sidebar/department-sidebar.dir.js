(function() {

  'use strict';

  angular
    .module('iserveu')
    .directive('departmentSidebar', departmentSidebar);

  function departmentSidebar() {

    return {

      templateUrl: 'app/components/department/department-sidebar/department-sidebar.tpl.html'
      
    }

  }
  
})();