(function() {

  'use strict';

  angular
    .module('app.sidebar')
    .component('sidebarComponent', {
      controller: SidebarController,
      template: `
        <motion-sidebar-component ng-if="location !== '/user-manager'" flex></motion-sidebar-component>
        <user-sidebar ng-if="location === '/user-manager'" flex></user-sidebar>
      `
    });

  SidebarController.$inject = ['$scope', '$location'];

  function SidebarController($scope, $location) {
    $scope.location = $location.path();
    $scope.$on('$locationChangeStart', function () {
      $scope.location = $location.path();
    });
  }

})();
