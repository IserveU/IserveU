'use strict';
(function(window, angular, undefined) {

  angular
    .module('app.api')
    .factory('MotionDepartmentResource', [
      '$resource',
      '$q',
      MotionDepartmentResource]);

  function MotionDepartmentResource($resource, $q) {

    /****************************************************************
    *
    * Resource setters using Angular's internal ngResource.
    *
    *****************************************************************/

    var Department = $resource('api/department/:id', {}, {
      'update': { method: 'PUT' },
      'cache': true
    });

    /*****************************************************************
    *
    * Server-side functions.
    *
    ******************************************************************/

    function getDepartments() {
      return Department.get().$promise.then(function(success) {
        return success;
      }, function(error) {
        return $q.reject(error);
      });
    }

    function addDepartment(data) {
      return Department.save(data).$promise.then(function(success) {
        return success;
      }, function(error) {
        return $q.reject(error);
      });
    }

    function deleteDepartment(id) {
      return Department.delete({id: id}).$promise.then(function(success) {
        return success;
      }, function(error) {
        return $q.reject(error);
      });
    }

    function updateDepartment(data) {
      return Department.update({id: data.id}, data)
        .$promise.then(function(success) {
          return success;
        }, function(error) {
          return $q.reject(error);
        });
    }


    return {
      getDepartments: getDepartments,
      addDepartment: addDepartment,
      deleteDepartment: deleteDepartment,
      updateDepartment: updateDepartment
    };
  }
})(window, window.angular);
