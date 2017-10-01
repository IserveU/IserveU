(function() {

  angular
    .module('app.comment')
    .component('commentListComponent', {
      bindings: {
        motion: '<'
      },
      controller: CommentListController,
      templateUrl: 'app/components/comment/comment-list.tpl.html'
    });

  CommentListController.$inject = ['$rootScope','$scope','CommentVoteResource','Utils'];

  function CommentListController($rootScope, $scope, CommentVoteResource, Utils) {
    var self = this;

    self.selectedIndex = 0;
    self.count = Utils.count;

    self.$onInit = function() {
      Utils.waitUntil(
      function tryMethod() {
        return !Utils.objectIsEmpty(self.motion)
      },
      function fetchItems() {
        fetchSelectedIndex();
        fetchUserCommentVotes();
      });
    }

    function fetchUserCommentVotes() {
      if (Utils.nullOrUndefined($rootScope.authenticatedUser)) {
        return false;
      }
      CommentVoteResource.getUserCommentVotes(self.motion.id).then(function(results) {
        self.commentVoteList = results.data;
      });
    }

    function fetchSelectedIndex(_userVote) {
      var vote = _userVote || self.motion._userVote;
      if (!vote || vote.position === 'undefined') {
        return;
      } else if (vote.position === 1) {
        self.selectedIndex = 0;
      } else if (vote.position === 0) {
        self.selectedIndex = 1;
      } else if (vote.position === -1) {
        self.selectedIndex = 2;
      }
    }

    $scope.$watch('$ctrl.motion._userVote', function(vote) {
      if (vote && vote.id) {
        fetchSelectedIndex(vote);
      }
    }, true);


  }
})();
