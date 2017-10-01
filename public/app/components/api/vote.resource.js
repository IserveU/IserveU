'use strict';
(function(window, angular, undefined) {

  angular
    .module('app.api')
    .factory('VoteResource', ['$resource', '$q', VoteResource]);

  function VoteResource($resource, $q) {

    /****************************************************************
    *
    * Resource setters using Angular's internal ngResource.
    *
    *****************************************************************/

    var MyVotes = $resource('api/user/:id/vote', {limit: '@limit'}, {
      query: {
        method: 'GET',
        params: {},
        isArray: true,
        ignoreLoadingBar: true
      }
    });

    var UpdateVote = $resource('api/vote/:id', {}, {
      'update': {
        method: 'PUT',
        ignoreLoadingBar: true
      }
    });

    var Vote = $resource('api/motion/:motion_id/vote/:id',
      {ignoreLoadingBar: '@true'});

    /*****************************************************************
    *
    * Server-side functions.
    *
    ******************************************************************/

    /**
    *
    *   @params: {user_id: number, motion_id: number, position: number}
    */
    function castVote(data) {
      return Vote.save({motion_id: data.motion_id}, {position: data.position})
        .$promise.then(function(success) {
          return success;
        }, function(error) {
          return $q.reject(error);
        });
    }

    /**
    *   @params: {id: number, user_id:
    *   number, motion_id: number, position: number}
    */
    function updateVote(data) {
      return UpdateVote.update({id: data.id}, {position: data.position})
        .$promise.then(function(success) {
          return success;
        }, function(error) {
          return $q.reject(error);
        });
    }

    function getMyVotes(id, limit) {
      return MyVotes.get({id: id}, limit).$promise.then(function(results) {
        return results;
      }, function(error) {
        return $q.reject(error);
      });
    }

    function getUsersVotes() {
      return Vote.query().$promise.then(function(result) {
        return result;
      }, function(error) {
        return $q.reject(error);
      });
    }


    return {
      castVote: castVote,
      updateVote: updateVote,
      getMyVotes: getMyVotes,
      getUsersVotes: getUsersVotes
    };
  }
}(window, window.angular));
