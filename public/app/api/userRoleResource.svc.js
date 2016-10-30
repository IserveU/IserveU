'use strict';
(function(window, angular, undefined) {


  angular
    .module('iserveu')
    .factory('userRoleResource', ['$http', '$q', userRoleResource]);

  function userRoleResource($http, $q) {

    function getRoles() {
      return $http.get('api/role/').then(function(results) {
        return results;
      }, function(error) {
        return $q.reject(error);
      });
    }

    function grantRole(data) {
      return $http.patch('api/user/' + data.slug + '/role/' + data.name)
      .then(function(results) {
        return results;
      }, function(error) {
        return $q.reject(error);
      });
    }

    function getUserRole(slug) {
      return $http.get('api/user/' + slug + '/role').then(function(results) {
        return results;
      }, function(error) {
        return $q.reject(error);
      });
    }

    function deleteUserRole(data) {
      return $http.delete('api/user/' + data.slug + '/role/' + data.name)
      .then(function(results) {
        return results;
      }, function(error) {
        return $q.reject(error);
      });
    }

    return {
      getRoles: getRoles,
      grantRole: grantRole,
      getUserRole: getUserRole,
      deleteUserRole: deleteUserRole
    };
  }
}(window, window.angular));
