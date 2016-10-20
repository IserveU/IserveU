'use strict';
(function(window, angular, undefined) {

  angular
    .module('iserveu')
    .factory('MotionVotes', [
      '$translate',
      'motionVoteStatusbarService',
      MotionVotesFactory]);

  function MotionVotesFactory($translate, motionVoteStatusbarService) {

    function MotionVotes(motionVoteData) {

      // I wonder if this is something i should be doing,
      // because ... one of hte pros of using models
      // is to have contracts; so if something doesn't
      // exist then to destroy it?
      if (motionVoteData) {
        this.setData(motionVoteData);
      }

      this.overallPosition = {};
    }

    var overallPosition = {
      default: {
        icon: 'thumbs-up-down',
        message: 'There have been no votes'
      },
      loading: {
        icon: 'loading',
        message: ''
      },
      agree: {
        icon: 'thumb-up',
        message: 'Most people agree'
      },
      disagree: {
        icon: 'thumb-down',
        message: 'Most people disagree'
      },
      abstain: {
        icon: 'thumbs-up-down',
        message: 'Most people abstained'
      },
      tie: {
        icon: 'thumbs-up-down',
        message: 'There has been a majority tie'
      }
    };

    MotionVotes.prototype = {
      setData: function(motionVoteData) {
        angular.extend(this, motionVoteData);
        motionVoteStatusbarService.setStatusbar(this);
      },

      setOverallPosition: function(overallPosition) {
        this.setData({overallPosition: overallPosition});
      },

      setOverallPositionLoading: function() {
        this.setData({ overallPosition: overallPosition.loading });
      },

      getOverallPosition: function() {
        extractData(this);
      },

      reload: function(motionVoteData) {
        // resets motion vote data
        angular.extend(this, {abstain: null, agree: null, disagree: null});
        this.setData(motionVoteData);
        return this;
      }
    };

    function extractData(votes) {
      var _votes = votes.data || votes;

      function parse(key, type) {
        if (key && key.active && key.active.number) {
          return key.active.number;
        }
        return 0;
      }

      // using this for the time being
      var _d = angular.extend({}, {
        abstain:  parse(_votes.abstain),
        agree:    parse(_votes.agree),
        disagree: parse(_votes.disagree)
      });

      // literally every permutation ... hopefully.
      if (_d.abstain === _d.agree && _d.abstain > _d.disagree ||
          _d.abstain === _d.disagree && _d.abstain > _d.agree ||
          _d.agree === _d.disagree && _d.agree > _d.abstain ||
          (_d.agree === _d.disagree && (_d.agree === _d.abstain) &&
          _d.agree !== null)) {

        votes.setOverallPosition(overallPosition.tie);
        return 'tie';
      } else if (!_d.abstain && !_d.agree && !_d.disagree) {

        votes.setOverallPosition(overallPosition.default);
        return 'no votes';
      } else if (_d.disagree && (!_d.agree || !_d.abstain) ||
        _d.disagree > _d.agree && _d.disagree > _d.abstain) {

        votes.setOverallPosition(overallPosition.disagree);
        return 'disagree';
      } else if (_d.agree && (!_d.disagree || !_d.abstain) ||
        _d.agree > _d.disagree && _d.agree > _d.abstain) {

        votes.setOverallPosition(overallPosition.agree);
        return 'agree';
      } else if (_d.abstain && (!_d.disagree || !_d.agree) ||
        _d.abstain > _d.disagree && _d.abstain > _d.agree) {

        votes.setOverallPosition(overallPosition.abstain);
        return 'abstain';
      }
    }

    return MotionVotes;

  }

})(window, window.angular);
