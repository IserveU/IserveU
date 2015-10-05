(function() {

  'use strict';

  angular
    .module('iserveu')
    .directive('userSidebar', userSidebar);

  function userSidebar($rootScope, SetPermissionsService) {

  	if(!SetPermissionsService.can('administrate-users')){
  	return {

      templateUrl: 'app/components/motion/motion-sidebar/motion-sidebar.tpl.html'
      
      }

  	}
    else if(SetPermissionsService.can('administrate-users')){
    return {

      templateUrl: 'app/components/user/user-sidebar/user-sidebar.tpl.html'
            
    }
	}
  }
})();