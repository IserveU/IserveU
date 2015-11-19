(function() {

  'use strict';

  angular
    .module('iserveu')
    .directive('userSidebar', userSidebar);

  function userSidebar($rootScope, SetPermissionsService) {

  	return {

      templateUrl: SetPermissionsService.can('administrate-users') ? 'app/components/user/user-sidebar/user-sidebar.tpl.html' :'app/components/motion/motion-sidebar/motion-sidebar.tpl.html'
      
      }

  	}

})();