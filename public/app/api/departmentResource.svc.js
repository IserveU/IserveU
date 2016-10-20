'use strict';
(function(window, angular, undefined) {

  angular
    .module('iserveu')
    .factory('departmentResource', [
      '$http',
      '$q',
      'utils',
      departmentResource]);

  /**
   * Delegates and contains API CRUD interactions from
   * client-side to server-side.
   * @param  {Service} $http
   * @param  {Service} $q
   * @param  {Service} utils
   * @return {promise} Server-side promise
   */
  function departmentResource($http, $q, utils) {
    var index = {};

    /**
     * Department index
     * @return {promise} promise or cache
     */
    function getDepartments() {
      if (!utils.objectIsEmpty(index)) {
        return $q.when({data: index});
      }

      return $http({
        method: 'GET',
        url: '/api/department',
        ignoreLoadingBar: true,
      }).success(function(results) {
        index = results;
        return results;
      }).error(function(error) {
        return error;
      });
    }

    return {
      getDepartments: getDepartments,
      index: index,
    };
  }
})(window, window.angular);
