'use strict';
(function(window, angular, undefined) {

  angular
    .module('iserveu')
    .factory('communityResource', [
      '$http',
      '$q',
      'utils',
      communityResource]);

  function communityResource($http, $q, utils) {
    var index = {};

    /**
     * Get all communities
     * @return {promise} promise or cached index
     */
    function getCommunities() {
      if (!utils.objectIsEmpty(index)) {
        return $q.when({data: index});
      }

      return $http({
        method: 'GET',
        url: '/api/community',
        ignoreLoadingBar: true
      }).success(function(results) {
        index = results;
        return results;
      }).error(function(error) {
        return error;
      });
    }

    /**
     * TODO 2017/01/18 "this function looks usless" - Victor
     * grab community given the id
     * @param  {number} id
     * @return {promise}    promise or cached item
     */
    function retrieveNameById(id) {
      if (utils.objectIsEmpty(index)) {
        return getCommunities().then(function(results) {
          return retrieveNameById(id);
        });
      } else {
        var name;
        return $q.when(name);
      }
    }

    return {
      getCommunities: getCommunities,
      retrieveNameById: retrieveNameById,
      index: index
    };
  }
})(window, window.angular);
