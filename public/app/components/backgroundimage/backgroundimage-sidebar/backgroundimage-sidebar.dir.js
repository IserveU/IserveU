(function() {

  'use strict';

  angular
    .module('iserveu')
    .directive('backgroundimageSidebar', backgroundimageSidebar)
    .directive('backgroundimage.previewSidebar', backgroundimagePreviewSidebar);

  function backgroundimageSidebar(SetPermissionsService) {

    return {

      templateUrl: SetPermissionsService.can('administrate-background_images') ? 'app/components/backgroundimage/backgroundimage-sidebar/backgroundimage-sidebar.tpl.html' :'app/components/motion/motion-sidebar/motion-sidebar.tpl.html'
      
    }
  }

  function backgroundimagePreviewSidebar() {

    return {

      templateUrl: SetPermissionsService.can('administrate-background_images') ? 'app/components/backgroundimage/backgroundimage-sidebar/backgroundimage-sidebar.tpl.html' :'app/components/motion/motion-sidebar/motion-sidebar.tpl.html'
      
    }
  }

  
})();