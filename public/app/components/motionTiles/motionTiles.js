(function() {

  angular
    .module('iserveu')
    .directive('motionTiles', [ 'Motion', motionTiles ]);

    function motionTiles() {

    function motionTilesController($scope, $mdMedia, Motion) {

      $scope.getRankTile = function(rank) {
        var overallPosition = {
          loading: {
            icon: 'loading',
            message: ''
          },
          agree: {
            icon: 'thumb-up',
            message: 'Majority agree'
          },
          disagree: {
            icon: 'thumb-down',
            message: 'Majority disagree'
          },
          tie: {
            icon: 'thumbs-up-down',
            message: 'Majority tie'
          }
        };

        if (rank < 0 )
          return overallPosition.disagree;

        if (rank > 0)
          return overallPosition.agree;

        if (rank === 0)
          return overallPosition.tie;

        return overallPosition.loading;
      }

      $scope.direction = $mdMedia('gt-sm') ? 'left' : '';
    }

    return {
      controller: ['$scope', '$mdMedia', motionTilesController],
      controllerAs: 'motionTiles',
      templateUrl: 'app/components/motionTiles/motionTiles.tpl.html'
    }


  }

})();
