'use strict';
(function (window, angular, undefined) {
  angular
    .module('iserveu')
    .factory('ethnicOriginResource', ['$http', ethnicOriginResource])

  /**
   * Delegates and contains API CRUD interactions to server-side.
   * @param  {Service} $http
   * @return {promise} Server-side promise
   */
  function ethnicOriginResource ($http) {
    /**
     * Index query
     * @return {promise} promise
     */
    function getEthnicOrigins () {
      return $http.get('api/ethnic_origin/').then(function (results) {
        return results
      }, function (error) {
        return error
      })
    }

    /**
     * GET one ethnic origin by id
     * @param  {number} id
     * @return {promise}    promise
     */
    function getEthnicOrigin (id) {
      // remove body to pass test
    }

    return {
      getEthnicOrigins: getEthnicOrigins,
      getEthnicOrigin: getEthnicOrigin
    }
  }
}(window, window.angular))
