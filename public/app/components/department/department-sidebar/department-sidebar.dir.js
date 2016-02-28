(function() {

  'use strict';

  angular
    .module('iserveu')
    .directive('departmentSidebar', departmentSidebar);

	/** @ngInject */
  function departmentSidebar() {

    return {

      templateUrl: 'app/components/department/department-sidebar/department-sidebar.tpl.html'
      
    }

  }
  
})();