(function() {

angular
  .module('iserveu')
  .directive('sidebarSearch', sidebarSearch);

  function sidebarSearch() {

    function sidebarSearchController($scope, $location) {
      $scope.location = $location.path();
      $scope.$on('$locationChangeStart', function () {
        $scope.location = $location.path();
      });
    }

    sidebarSearchController.$inject = ['$scope', '$location'];

    return {
      controller: sidebarSearchController,
      controllerAs: 'sidebarSearch',
      templateUrl: 'app/components/sidebar/sidebarSearch.tpl.html'
    }
  }
})();
