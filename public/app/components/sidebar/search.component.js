(function() {

  'use strict';

  angular
    .module('app.sidebar')
    .component('sidebarSearchComponent', {
      controller: SidebarSearchController,
      template: `
        <motion-search-component ng-if="location !== '/user-manager'" flex></motion-search-component>
        <user-search ng-if="location === '/user-manager'" flex></user-search>
      `
    });

  SidebarSearchController.$inject = ['$scope', '$location'];

  function SidebarSearchController($scope, $location) {
    $scope.location = $location.path();
    $scope.$on('$locationChangeStart', function () {
      $scope.location = $location.path();
    });
  }

})();
