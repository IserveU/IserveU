'use strict';
angular
  .module('iserveu')
  .controller('emailVoteController',
    ['$state',
    '$stateParams',
    '$location',
    'voteResource',
  emailVoteController]);

  function emailVoteController($state, $stateParams, $location, voteResource) {

    if (!$stateParams.slug || getPosition($stateParams.position) === undefined) {
      return $state.go('home');
    }

    var data = {
      motion_id: $stateParams.slug,
      position: getPosition($stateParams.position)
    };

    voteResource.castVote(data).then(function(results) {
      $state.go('motion', {'id': $stateParams.slug});
    }, function (error) {
      $state.go('home');
    });

    function getPosition(position) {
      if (position === 'agree')
        return 1;

      if (position === 'disagree')
        return -1;
    }
  }
