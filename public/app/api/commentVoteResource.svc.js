'use strict';
(function(window, angular, undefined) {

  angular
    .module('iserveu')
    .factory('commentVoteResource', [
      '$resource',
      '$http',
      '$q',
      'utils',
      commentVoteResource]);

  function commentVoteResource($resource, $http, $q, utils) {
    var _userCommentVoteIndex = {};
    var CommentVote = $resource('api/comment_vote/:id', {}, {
      'update': {method: 'PUT'},
    });
    var SaveCommentVote = $resource('api/comment/:id/comment_vote',
      {ignoreLoadingBar: '@true'});

    /**
     * Comment votes query.
     * @param  {object} data
     * @return {promise} promise or cache user index
     */
    function getUserCommentVotes(data) {
      if (!utils.objectIsEmpty(_userCommentVoteIndex)) {
        return $q.when({data: _userCommentVoteIndex});
      }

      return $http({
        method: 'GET',
        url: 'api/user/' + data.user_id + '/comment_vote',
        params: {
          ignoreLoadingBar: true,
        },
      }).success(function(results) {
        _userCommentVoteIndex = results.data || results;
        return results;
      }).error(function(error) {
        return error.data || error;
      });
    }

    /**
     * Save comment
     * @param  {object} data
     * @return {promise}      promise
     */
    function saveCommentVote(data) {
      return SaveCommentVote.save(
        {id: data.comment_id},
        {position: data.position})
        .$promise.then(successHandler).catch(errorHandler);
    }

    /**
     * Update comment vote
     * @param  {object} data
     * @return {promise}      promise
     */
    function updateCommentVote(data) {
      return CommentVote.update(
        {id: data.id},
        {position: data.position})
        .$promise.then(successHandler).catch(errorHandler);
    }

    /**
     * Delete comment vote
     * @param  {object} data
     * @return {promise}      promise
     */
    function deleteCommentVote(data) {
      return CommentVote.delete({id: data.id})
        .$promise.then(function(results) {
          successHandler(results, data.id);
        }).catch(errorHandler);
    }

    /**
     * Success handler that transforms data
     * @param  {results} res
     * @param  {number} id
     * @return {body} transformed data
     */
    function successHandler(res, id) {
      var body = res.data || res;

      if (!utils.objectIsEmpty(_userCommentVoteIndex)) {
        for (var i in _userCommentVoteIndex) {
          if (body.id === _userCommentVoteIndex[i].id) {
            if (id !== undefined) {
              delete _userCommentVoteIndex[i];
            } else {
              _userCommentVoteIndex[i] = body;
            }
          }
        }
      }

      return body;
    }

    /**
     * Reject promise
     * @param  {Error} error
     * @return {rejection}       $q.reject promise
     */
    function errorHandler(error) {
      return $q.reject(error);
    }

    return {
      _userCommentVoteIndex: _userCommentVoteIndex,
      getUserCommentVotes: getUserCommentVotes,
      saveCommentVote: saveCommentVote,
      updateCommentVote: updateCommentVote,
      deleteCommentVote: deleteCommentVote,
    };
  }
}(window, window.angular));
