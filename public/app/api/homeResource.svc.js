'use strict';
(function(window, angular, undefined) {

  angular
    .module('iserveu')
    .factory('homeResource', [
      '$http',
      '$rootScope',
      '$q',
      'utils',
      homeResource]);

  function homeResource($http, $rootScope, $q, utils) {

    /****************************************************************
    *
    * Resource setters @deprecated Angular's internal ngResource.
    *
    *****************************************************************/

    var _api = {
      myComments:  '/api/user/:id/comment',
      myVotes:     '/api/user/:id/vote',
      topComments: '/api/comment',
      topMotion:   '/api/motion'
    };

    var cacheData = {
      myComments: {},
      myVotes: {},
      topComments: {},
      topMotion: {}
    };

    var getMyComments = function() {
      return query(regexReplace(_api.myComments), 'myComments');
    };

    var getMyVotes = function() {
      return query(regexReplace(_api.myVotes), 'myVotes');
    };

    var getTopComments = function() {
      return query(_api.topComments, 'topComments');
    };

    var getTopMotion = function() {
      return query(_api.topMotion, 'topMotion', {rank_greater_than: 0});
    };

      /*****************************************************************
      *
      * Private Functions
      *
      ******************************************************************/

    // TODO: make this more functional, but at the moment just hardcoded.
    function regexReplace(string, regex) {
      var id = $rootScope.authenticatedUser.id;
      return string.replace(':id', id);
    }

    function query(_endpoint, key, data) {

      if (!utils.objectIsEmpty(cacheData[key])) {
        return $q.when({data: cacheData[key]});
      }

      return $http({
        method: 'GET',
        isArray: true,
        url: _endpoint,
        data: data || {},
        ignoreLoadingBar: true
      }).success(function(results) {
        cacheData[key] = results;
        return results;
      }).error(function(error) {
        return error;
      });
    }

    return {
      getMyComments: getMyComments,
      getMyVotes: getMyVotes,
      getTopComments: getTopComments,
      getTopMotion: getTopMotion
    };
  }
}(window, window.angular));
