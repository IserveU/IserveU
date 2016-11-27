'use strict';
(function(window, angular, undefined) {

  angular
    .module('iserveu')
    .factory('communityResource', [
      '$http',
      '$cacheFactory',
      '$q',
      'utils',
      communityResource]);

  function communityResource($http, $cacheFactory, $q, utils) {

    /**
     * Get all communities
     * @return {promise} promise or cached index
     */
    function getCommunities() {
      return $http({
        method: 'GET',
        url: '/api/community',
        cache: true,
        ignoreLoadingBar: true
      }).success(function(results) {
        return results;
      }).error(function(error) {
        return error;
      });
    }

    /**
     * Grab the community given the id
     * @param  {number} id
     * @return {promise}    promise or cached item
     */
    function retrieveNameById(id) {

      var communities = $cacheFactory.get('$http').get('/api/community'),
          name;

      if (angular.isUndefined(communities)) {
        return getCommunities().then(function(results) {
          return retrieveNameById(id);
        });
      } else {
        // parse string to json
        communities = JSON.parse(communities[1]);

        communities.forEach(function(community) {
          if (community.id === id)
            name = community.name;
        });


        console.log(name);
        return $q.when(name);
      }
    }

    return {
      getCommunities: getCommunities,
      retrieveNameById: retrieveNameById
    };
  }
})(window, window.angular);
