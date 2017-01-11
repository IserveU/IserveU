'use strict';
angular
  .module('iserveu')
  .controller('emailVoteController',
    ['$state',
    '$stateParams',
    'voteResource',
    '$location',
    function ($state, $stateParams, $location, voteResource) {

      if (!$stateParams.slug || getPosition($stateParams.position) === undefined) {
        console.log('went in');
        return $state.go('home')
      }

      var data = {
        motion_id: $stateParams.slug,
        position: getPosition($stateParams.position)
      }

      voteResource.castVote(data).then(function(results) {
        $location.path('#/motion/' + $stateParams.slug)
      }, function (error) {
        return $state.go('login');
      });

      function getPosition(position) {
        if (position === 'agree')
          return 1;

        if (position === 'disagree')
          return -1;
      }
    }
]);
