'use strict';
(function (window, angular, undefined) {
  angular
    .module('app.api')
    .factory('HomeResource', [
      '$http',
      '$rootScope',
      '$q',
      'Utils',
      HomeResource])

  function HomeResource ($http, $rootScope, $q, Utils) {
    /****************************************************************
    *
    * Resource setters @deprecated Angular's internal ngResource.
    *
    *****************************************************************/

    var _api = {
      myComments: '/api/user/:id/comment',
      myVotes: '/api/user/:id/vote',
      topComments: '/api/comment',
      topMotion: '/api/motion'
    }

    var cacheData = {
      myComments: {},
      myVotes: {},
      topComments: {},
      topMotion: {}
    }

    var getMyComments = function () {
      return query(regexReplace(_api.myComments), 'myComments')
    };

    var getMyVotes = function () {
      return query(regexReplace(_api.myVotes), 'myVotes')
    };

    var getTopComments = function () {
      return query(_api.topComments, 'topComments', {
        'orderBy[commentRank]': 'desc'
      })
    };

    var getTopMotion = function () {
      return query(_api.topMotion, 'topMotion', {
        'orderBy[_rank]': 'desc'
      })
    };

      /*****************************************************************
      *
      * Private Functions
      *
      ******************************************************************/

    // TODO: make this more functional, but at the moment just hardcoded.
    function regexReplace (string, regex) {
      var id = $rootScope.authenticatedUser.id
      return string.replace(':id', id)
    }

    function query (_endpoint, key, data) {
      if (!Utils.objectIsEmpty(cacheData[key])) {
        return $q.when({data: cacheData[key]})
      }

      return $http({
        method: 'GET',
        isArray: true,
        url: _endpoint,
        data: data || {}, /* Why is this here, what is it doing? */
        params: data,
        ignoreLoadingBar: true
      }).then(function (results) {
        cacheData[key] = results
        return results
      }, function (error) {
        return error
      })
    }

    return {
      getMyComments: getMyComments,
      getMyVotes: getMyVotes,
      getTopComments: getTopComments,
      getTopMotion: getTopMotion
    }
  }
}(window, window.angular))
