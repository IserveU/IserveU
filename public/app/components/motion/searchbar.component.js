(function() {

  'use strict';
  
  angular
    .module('app.motions')
    .component('motionSearchComponent', {
      controller: MotionSearchController,
      templateUrl: resolveTemplate
    });

  MotionSearchController.$inject = ['MotionSearch', 'Settings'];

  function MotionSearchController(MotionSearch, Settings) {
    this.search = MotionSearch;
    this.showMotionView = Settings.get('motion.on');
  }

  resolveTemplate.$inject = ['$mdMedia'];
  function resolveTemplate($mdMedia) {
    return $mdMedia('gt-sm') ?
      'app/components/motion/searchbar/motionSearchbar_toolbar.tpl.html' :
      'app/components/motion/searchbar/motionSearchbar.tpl.html';
  }

})();