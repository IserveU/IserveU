(function() {

  'use strict';

  angular
    .module('iserveu')
    .directive('propertySidebar', propertySidebar);

  function propertySidebar($state, $rootScope) {

    return {
      templateUrl: 'app/components/motion/motion-sidebar/motion-sidebar.tpl.html'
    }
  }
  
})();