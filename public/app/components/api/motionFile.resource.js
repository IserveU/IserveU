'use strict';
(function(window, angular, undefined) {

  angular
    .module('app.api')
    .factory('MotionFileResource', [
      '$resource',
      '$q',
      '$http',
      MotionFileResource]);

  function MotionFileResource($resource, $q, $http) {

    /****************************************************************
    *
    * Resource setters using Angular's internal ngResource.
    *
    *****************************************************************/
    var MotionFile = $resource('api/motion/:motion_id/motionfile/:file_id',
      {motion_id: '@motion_id', file_id: '@file_id'}, {
        'update': { method: 'PUT' }
      });

    /*****************************************************************
    *
    * Server-side functions.
    *
    ******************************************************************/

    function getMotionFiles(motion_id) {
      return MotionFile.query({motion_id: motion_id})
        .$promise.then(function(results) {
          return results;
        }, function(error) {
          return $q.reject(error);
        });
    }

    function getMotionFile(motion_id, file_id) {
      return MotionFile.query({motion_id: motion_id, file_id: file_id})
        .$promise.then(function(results) {
          return results;
        }, function(error) {
          return $q.reject(error);
        });
    }

    // might function wonky, if that happens add a method for PUT
    function updateMotionFile(data, motion_id, file_id) {
      return MotionFile.update(data, {motion_id: motion_id, file_id: file_id})
        .$promise.then(function(results) {
          return results;
        }, function(error) {
          return $q.reject(error);
        });
    }

    function deleteMotionFile(motion_id, file_id) {
      return MotionFile.delete({motion_id: motion_id, file_id: file_id})
        .$promise.then(function(results) {
          return results;
        }, function(error) {
          return $q.reject(error);
        });
    }

    return {
      getMotionFiles: getMotionFiles,
      getMotionFile: getMotionFile,
      updateMotionFile: updateMotionFile,
      deleteMotionFile: deleteMotionFile
    };
  }
})(window, window.angular);
