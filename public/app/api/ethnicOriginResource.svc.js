'use strict';
(function(window, angular, undefined) {

  angular
    .module('iserveu')
    .factory('ethnicOriginResource', ['$http', ethnicOriginResource]);

  /**
   * Delegates and contains API CRUD interactions to server-side.
   * @param  {Service} $http
   * @return {promise} Server-side promise
   */
  function ethnicOriginResource($http) {
    /**
     * Index query
     * @return {promise} promise
     */
    function getEthnicOrigins() {
      return $http.get('api/ethnic_origin/').success(function(results) {
        return results;
      }).error(function(error) {
        return error;
      });
    }

    /**
     * GET one ethnic origin by id
     * @param  {number} id
     * @return {promise}    promise
     */
    function getEthnicOrigin(id) {
      return $http.get('api/ethnic_origin/' + id).success(function(results) {
        return results;
      }).error(function(error) {
        return error;
      });
    }

    return {
      getEthnicOrigins: getEthnicOrigins,
      getEthnicOrigin: getEthnicOrigin
    };
  }
}(window, window.angular));
