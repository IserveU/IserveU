(function() {

angular
  .module('iserveu')
  .directive('sidebar', sidebar);

  function sidebar() {

    function sidebarController() {

      // pass

    }

    return {
      controller: sidebarController,
      controllerAs: 'sidebar',
      templateUrl: 'app/components/sidebar/sidebar.tpl.html'
    }
  }
})();
