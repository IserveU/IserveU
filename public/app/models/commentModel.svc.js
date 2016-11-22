'use strict';
(function(window, angular, undefined) {

  angular
    .module('iserveu')
    .factory('Comment', [
      '$http',
      'commentResource',
      'ToastMessage',
      CommentFactory]);

  function CommentFactory($http, commentResource, ToastMessage) {

    function Comment(commentData) {
      if (commentData) {
        this.setData(commentData);
      }

      this.id = commentData ? commentData.id : null;
      this.posting = false;
      this.exists = false;
      this.status = 'public';
    }

    Comment.prototype = {

      setData: function(commentData) {
        angular.extend(this, commentData);
      },

      reloadComment: function(commentData) {
        // this = angular.copy(commentData);
      },

      submit: function(motion) {
        submit(this, motion);
      },

      update: function(motion) {
        update(this, motion);
      },

      delete: function(motion) {
        deleteComment(this, motion);
      }

    };

    /**
    * Private functions
    */
    function submit(comment, motion) {

      var self = comment;
      self.posting = true;

      commentResource.saveComment({
        vote_id: motion.userVote.id,
        text: self.text,
        status: self.status
      }).then(function(success) {
        self.posting = false;
        self.exists = true;
        self.id = success.id;
        motion.getMotionComments(motion.id);
      }, function(error) {
        self.posting = false;
      });
    }

    function update(comment, motion) {

      var self = comment;
      commentResource.updateComment({
        id: self.id,
        text: self.text,
        status: self.status
      }).then(function(success) {
        self.posting = false;
        self.exists = true;
        motion.getMotionComments(motion.id);
      }, function(error) {
        self.posting = false;
      });
    }

    function deleteComment(comment, motion) {
      var self = comment;
      ToastMessage.destroyThis('comment', function() {
        commentResource.deleteComment(self.id).then(function(results) {
          self.exists = false;
          self.setData({id: null, text: null, status: null, posting: null, exists: null});
          motion.getMotionComments(motion.id);
        });
      });
    }

    return Comment;
  }
})(window, window.angular);
