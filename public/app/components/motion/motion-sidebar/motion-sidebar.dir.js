(function() {

  'use strict';

  angular
    .module('iserveu')
    .directive('motionSidebar', motionSidebar);

  function motionSidebar($state, $rootScope) {

    return {
      templateUrl: 'app/components/motion/motion-sidebar/motion-sidebar.tpl.html'
    }
  }
  
})();