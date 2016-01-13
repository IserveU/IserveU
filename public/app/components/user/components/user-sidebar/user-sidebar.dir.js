(function() {

  'use strict';

  angular
    .module('iserveu')
    .directive('userSidebar', userSidebar);

  function userSidebar($rootScope, SetPermissionsService) {

  	return {

      templateUrl: SetPermissionsService.can('administrate-users') ? 

      	'app/components/user/components/user-sidebar/user-sidebar.tpl.html' :
      
      	'app/components/motion/components/motion-sidebar/motion-sidebar.tpl.html'
      
      }

  	}

})();