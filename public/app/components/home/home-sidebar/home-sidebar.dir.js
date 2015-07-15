(function() {

  'use strict';

  angular
    .module('iserveu')
    .directive('homeSidebar', homeSidebar);

  function homeSidebar() {

    return {

      templateUrl: 'app/components/motion/motion-sidebar/motion-sidebar.tpl.html'

    }
  }
  
})();