(function() {

  'use strict';

  angular
    .module('iserveu')
    .directive('userSidebar', ['Authorizer', userSidebar]);

   /** @ngInject */
  function userSidebar(Authorizer) {

  	return {

      templateUrl: Authorizer.canAccess('administrate-users') ? 

      	'app/components/user/components/user-sidebar/user-sidebar.tpl.html' :
      
      	'app/components/motion/components/motion-sidebar/motion-sidebar.tpl.html'
      
      }

  	}

})();