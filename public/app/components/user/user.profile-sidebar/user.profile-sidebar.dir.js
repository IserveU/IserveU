(function() {

  'use strict';

  angular
    .module('iserveu')
    .directive('user.profileSidebar', userprofileSidebar);

  function userprofileSidebar($rootScope) {

  	if(!$rootScope.administrateUsers){
  	return {

      templateUrl: 'app/components/motion/motion-sidebar/motion-sidebar.tpl.html'

      }

  	}
  	else {
    return {

      templateUrl: 'app/components/user/user-sidebar/user-sidebar.tpl.html'
      
    }
	}
  }
  
})();