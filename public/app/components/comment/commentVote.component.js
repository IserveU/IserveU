(function() {

  'use strict';

  angular
    .module('app.comment')
    .component('commentVoteComponent', {
      bindings: {
        motion: '<',
        position: '<',
        commentId: '<'
      },
      controller: CommentVoteController,
      templateUrl: 'app/components/comment/commentVote.tpl.html'
    });

  CommentVoteController.$inject = ['$rootScope','$scope','CommentVote', 'CommentVoteResource'];

  function CommentVoteController($rootScope, $scope, CommentVote, CommentVoteResource) {

    var self = this;
    self.button = new CommentVote();
    self.currentVote = 0;
    self.initialVote = 0;

    self.vote = function(id, pos) {

      if (self.button['agree'].isActive) {
        self.currentVote = pos === -1 ? pos : 0;
      } else if (self.button['disagree'].isActive) {
        self.currentVote = pos === 1 ? pos : 0;
      } else {
        self.currentVote = pos;
      }

      self.button.castVote(id, pos);
    }

    function fetchUserCommentVotes() {

      CommentVoteResource.getUserCommentVotes(self.motion.id).then(renderActiveCommentVotes);

      if (self.button.agree.isActive) {
        self.currentVote = 1;
      } else if (self.button.disagree.isActive) {
        self.currentVote = -1;
      } else {
        self.currentVote = 0;
      }

      self.initialVote = self.currentVote;
    }

    function renderActiveCommentVotes(res) {
      var comment_votes = res.data || res;

      for (var i in comment_votes) {
        if (comment_votes[i].comment_id === self.commentId) {
          self.button.setData(comment_votes[i]);
          self.button.setActive(comment_votes[i].position);
        }
      }
    }

    $scope.$watch('motion.motionComments', function(value, oldValue) {
      if (value !== undefined && $rootScope.authenticatedUser) {
        fetchUserCommentVotes();
      }
    }, true);

    (function init() {
      if (!$rootScope.authenticatedUser) {
        return false;
      }
      fetchUserCommentVotes();
    })();
  }




})();