(function() {

  'use strict';

  angular
    .module('app.header')
    .component('toolbarComponent', {
      templateUrl: 'app/components/header/toolbar.tpl.html',
      controller: ToolbarController
    });
  ToolbarController.$inject = ['$mdSidenav', 'Settings', 'MotionIndex', 'Page'];

  function ToolbarController($mdSidenav, Settings, MotionIndex, Page) {

    this.showMenuButton = false;
    this.showMotionView = Settings.get('motion.on');
    this.pages = Page;

    this.switchMenuButton = function() {
      this.showMenuButton = !this.showMenuButton;
    };

    this.toggleSidebar = function(id) {
      $mdSidenav(id).toggle().then( MotionIndex._load() );
      $mdSidenav(id).toggle();
    };

  }

})();