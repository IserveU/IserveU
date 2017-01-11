'use strict';
(function() {

  angular
    .module('iserveu')
    .controller('emailVoteController',
      ['$state',
      '$stateParams',
      'voteResource',
      function ($state, $stateParams, voteResource) {

        if (!$stateParams.slug || !$stateParams.position
            || getPosition($stateParams.position !== undefined))
          $state.go('home')

        var data = {
          user_id: '',
          motion_id: $stateParams.slug,
          position: 0
        }

        voteResource.castVote(data).then(function(results) {

        }, function (error) {

        });

        var getPosition = function (position) {
          if (position === 'agree')
            return 1;

          if (position === 'disagree')
            return -1;
        }

    }]);
})();
