(function() {

  'use strict';

  angular
    .module('iserveu')
    .directive('backgroundimageSidebar', backgroundimageSidebar);

  function backgroundimageSidebar() {

    return {

      templateUrl: 'app/components/backgroundimage/backgroundimage-sidebar/backgroundimage-sidebar.tpl.html'
      
    }
  }
  
})();