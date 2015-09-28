(function() {

  'use strict';

  angular
    .module('iserveu')
    .directive('backgroundimageSidebar', backgroundimageSidebar)
    .directive('backgroundimage.previewSidebar', backgroundimagePreviewSidebar);

  function backgroundimageSidebar() {

    return {

      templateUrl: 'app/components/backgroundimage/backgroundimage-sidebar/backgroundimage-sidebar.tpl.html'
      
    }
  }

  function backgroundimagePreviewSidebar() {

    return {

      templateUrl: 'app/components/backgroundimage/backgroundimage-sidebar/backgroundimage-sidebar.tpl.html'
      
    }
  }

  
})();