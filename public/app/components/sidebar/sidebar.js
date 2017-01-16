(function() {

angular
  .module('iserveu')
  .directive('sidebar', sidebar);

  function sidebar() {

    function SidebarController($scope, $location) {
      $scope.location = $location.path();
      $scope.$on('$locationChangeStart', function () {
        $scope.location = $location.path();
      });
    }

    SidebarController.$inject = ['$scope', '$location'];

    return {
      controller: SidebarController,
      controllerAs: 'sidebar',
      templateUrl: 'app/components/sidebar/sidebar.tpl.html'
    }
  }
})();
