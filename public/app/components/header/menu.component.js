(function() {

  'use strict';

  angular
    .module('app.header')
    .component('menuComponent', {
      templateUrl: 'app/components/header/menu.tpl.html',
      controller: menuController
    });

  menuController.$inject = ['$mdMedia', '$translate', 'Auth', 'Settings'];

  function menuController($mdMedia, $translate, Auth, Settings) {

    this.$mdMedia = $mdMedia;
    this.showMotionView = Settings.get('motion.on');

    this.logout = function() {
      Auth.logout();
    };

/**================= Language Module not Currently Implemented ====*/
    this.preferredLang = "English";
    this.languages = [ {name:'English', key:'en'}, {name:'French', key:'fr'} ];

    this.changeLanguage = function(langKey){
      $translate.use(langKey);
    }
  }

})();