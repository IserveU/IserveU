(function() {

  'use strict';

  angular
    .module('iserveu')
    .directive('userSidebar', userSidebar);

  function userSidebar() {

    return {

      templateUrl: 'app/components/user/user-sidebar/user-sidebar.tpl.html'
      
    }
  }
  
})();