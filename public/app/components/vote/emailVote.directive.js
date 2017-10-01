// ? What is this being used for?
'use strict';
angular
  .module('iserveu')
  .directive('emailVote',
    ['$state',
    '$stateParams',
    '$location',
    'voteResource',
  emailVote]);

  function emailVote($state, $stateParams, $location, voteResource) {

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

  return {
    controller: ['$state', emailVoteController],
    template: `
      <div>
        <p> Thank you for voting!</p>
      </div>
    `
  }

  }
