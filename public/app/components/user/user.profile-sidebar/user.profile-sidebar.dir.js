(function() {

  'use strict';

  angular
    .module('iserveu')
    .directive('user.profileSidebar', userprofileSidebar);

  function userprofileSidebar() {

    return {

      templateUrl: 'app/components/user/user-sidebar/user-sidebar.tpl.html'
      
    }
  }
  
})();