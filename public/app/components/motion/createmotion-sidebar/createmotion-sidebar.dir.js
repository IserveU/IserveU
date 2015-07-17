(function() {

  'use strict';

  angular
    .module('iserveu')
    .directive('createmotionSidebar', createmotionSidebar);

  function createmotionSidebar() {

    return {

      templateUrl: 'app/components/motion/motion-sidebar/motion-sidebar.tpl.html'

    }
  }
  
})();