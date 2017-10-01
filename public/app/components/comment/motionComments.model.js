'use strict';
(function(window, angular, undefined) {

  angular
    .module('app.comment')
    .factory('MotionComments', [
      '$http',
      '$translate',
      MotionCommentsFactory]);

  function MotionCommentsFactory($http, $translate) {

    function MotionComments(motionVoteData) {
      if (motionVoteData) {
        this.setData(motionVoteData);
      }
    }

    MotionComments.prototype = {
      setData: function(motionVoteData) {
        angular.extend(this, motionVoteData);
      },

      setAgreeComments: function() {

      },

      setDisagreeComments: function() {

      }
    };


    return MotionComments;
  }

})(window, window.angular);
