'use strict';
(function (window, angular, undefined) {
  angular
    .module('iserveu')
    .factory('commentResource', [
      '$resource',
      '$http',
      '$q',
      commentResource])

  function commentResource ($resource, $http, $q) {
    var Comment = $resource('api/comment/:id', {}, {
      'update': {
        method: 'PUT',
        ignoreLoadingBar: true
      },
      'query': {
        method: 'GET',
        params: {},
        isArray: true,
        ignoreLoadingBar: true
      }
    })
    var Restore = $resource('api/comment/:id/restore')
    var CreateComment = $resource('api/vote/:voteId/comment',
      {ignoreLoadingBar: '@true'})

    /**
     * Get a single comment
     * @param  {number} id comment_id
     * @return {promise}    promise
     */
    function getComment (id) {
      return Comment.get({id: id}).$promise.then(function (results) {
        return results
      }, function (error) {
        return $q.reject(error)
      })
    }

    /**
     * Get array of comments.
     * @return {promise} promise
     */
    function getComments () {
      return Comment.query().$promise.then(function (results) {
        return results
      }, function (error) {
        return $q.reject(error)
      })
    }

    /**
     * The users comments.
     * @param  {object} data
     * @return {promise}      promise
     */
    function getUserComments (data) {
      return $http({
        method: 'GET',
        url: 'api/user/' + data.user_id + '/comment',
        params: {
          ignoreLoadingBar: true
        }
      }).then(function (results) {
        return results.data || results
      }, function (error) {
        return error.data || error
      })
    }

    /**
     * Save a comment
     * @param  {object} data
     * @return {promise} promise
     */
    function saveComment (data) {
      return CreateComment.save(
        {voteId: data.vote_id},
        {text: data.text, status: data.status}).$promise.then(function (success) {
          return success
        }, function (error) {
          return $q.reject(error)
        })
    }

    /**
     * update a comment
     * @param  {object} data
     * @return {promise}      promise
     */
    function updateComment (data) {
      return Comment.update(
        {id: data.id},
        {text: data.text, status: data.status}).$promise.then(function (success) {
          return success
        }, function (error) {
          return $q.reject(error)
        })
    }

    /**
     * delete a comment
     * @param  {number} id
     * @return {promise}    promise
     */
    function deleteComment (id) {
      return Comment.delete({id: id}).$promise.then(function (success) {
        return success
      }, function (error) {
        return $q.reject(error)
      })
    }

    /**
     * For soft deletes, @deprecated
     * @param  {number} id
     * @return {promise}    promise
     */
    function restoreComment (id) {
      return Restore.get({id: id}).$promise.then(function (success) {
        return success
      }, function (error) {
        return $q.reject(error)
      })
    }

    return {
      getComment: getComment,
      getComments: getComments,
      getUserComments: getUserComments,
      deleteComment: deleteComment,
      restoreComment: restoreComment,
      saveComment: saveComment,
      updateComment: updateComment
    }
  }
})(window, window.angular)
