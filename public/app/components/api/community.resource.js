'use strict';
(function (window, angular, undefined) {
  angular
    .module('app.api')
    .factory('CommunityResource', [
      '$http',
      '$q',
      'Utils',
      CommunityResource])

  function CommunityResource ($http, $q, Utils) {
    var index = {}

    /**
     * Get all communities
     * @return {promise} promise or cached index
     */
    function getCommunities () {
      if (checkCache()) return checkCache()
      
      return $http({
        method: 'GET',
        url: '/api/community',
        ignoreLoadingBar: true
      }).then(function (results) {
        index = results
        return index
      }, function (error) {
        return error
      })
      
    }

    function checkCache () {
      if (Utils.objectIsEmpty(index)) {
        return false
      }
      return $q.resolve(index)
    }

    /**
     * Not currently used, but it could be
     * @param  {number} id
     * @return {promise}    promise or cached item
     */
    function retrieveById (id) {
      return getCommunities().then(function (results) {
        for (var i = 0, len = results.data.data.length; i < len; i++) {
          if (results.data.data[i].id === id || results.data.data[i].slug === id) {
            return results.data.data[i]
          }
        }
        return null
      })
      
    }


    return {
      getCommunities: getCommunities,
      retrieveById: retrieveById,
      index: index
    }
  }
})(window, window.angular)
