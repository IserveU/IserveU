'use strict';
(function() {

  angular
    .module('iserveu')
    .controller('emailVoteController',
      ['$state',
      '$stateParams',
      'voteResource',
      function ($state, $stateParams, voteResource) {

        var data = {
          user_id: '',
          motion_id: '',
          position: 0
        }

        voteResource.castVote(data).then(function(results) {

        }, function (error) {

        });


    }]);
})();
