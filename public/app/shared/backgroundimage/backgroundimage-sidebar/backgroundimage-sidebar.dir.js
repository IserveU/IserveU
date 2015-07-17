(function() {

  'use strict';

  angular
    .module('iserveu')
    .directive('backgroundimageSidebar', backgroundimageSidebar);

  function backgroundimageSidebar() {

    return {

      templateUrl: 'app/shared/backgroundimage/backgroundimage-sidebar/backgroundimage-sidebar.tpl.html'
      
    }
  }
  
})();