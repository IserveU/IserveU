'use strict';
(function(window, angular, undefined) {

  angular
    .module('iserveu')
    .factory('userResource', [
      '$resource',
      '$q',
      '$rootScope',
      'authResource',
      userResource]);

  function userResource($resource, $q, $rootScope, authResource) {

    var customQuery = {
      query: {
        method: 'GET',
        params: {},
        isArray: true,
        ignoreLoadingBar: true,
        transformRequest: angular.identity
      }
    };


    /****************************************************************
    *
    * Resource setters using Angular's internal ngResource.
    *
    *****************************************************************/
    // var _userProfile = {}; TODO cache

    var User = $resource('api/user/:id', {}, {
      'update': { method: 'PUT' }
    });

    // var UserEdit = $resource('api/user/:id/edit'); TODO

    var UserIndex = $resource('api/user', {page: '@pnext_page'}, {
      query: {
        method: 'GET',
        ignoreLoadingBar: true,
        cancellable: true
      }
    });

    /*****************************************************************
    *
    * Server-side functions.
    *
    ******************************************************************/

    function getUsersIndex(next_page) {
      return UserIndex.query({page: next_page})
        .$promise.then(function(success) {
          return success;
        }, function(error) {
          return $q.reject(error);
        });
    }

    function getIndex() {
      return User.get().$promise.then(function(results) {
        return results;
      }, function(error) {
        return $q.reject(error);
      });
    }

    function getUserInfo(data) {
      return User.get(data).$promise.then(function(results) {
        return results;
      }, function(error) {
        return $q.reject(error);
      });
    }

    function getUser(id) {
      return User.get({id: id}).$promise.then(function(result) {
        return result;
      }, function(error) {
        return $q.reject(error);
      });
    }

    function storeUser(info) {
      return User.save(info).$promise.then(function(result) {
        return result;
      }, function(error) {
        return $q.reject(error);
      });
    }

    function updateUser(id, data) {
      return User.update({id: id}, data).$promise.then(function(result) {
        return result;
      }, function(error) {
        return $q.reject(error);
      });
    }

    function deleteUser(id) {
      return User.delete({id: id}).$promise.then(function(result) {
        return result;
      }, function(error) {
        return $q.reject(error);
      });
    }

    function getSelf() {
      if ($rootScope.authenticatedUser) {
        return $rootScope.authenticatedUser;
      } else if (localStorage.getItem('user')) {
        if (localStorage.getItem('user') === undefined)
          authResource.logout();
        else
          return JSON.parse(localStorage.getItem('user'));
      } else if ($rootScope.userIsLoggedIn)
        getSelf();
    }

    return {
      getIndex: getIndex,
      getUsersIndex: getUsersIndex,
      getUserInfo: getUserInfo,
      getUser: getUser,
      updateUser: updateUser,
      deleteUser: deleteUser,
      storeUser: storeUser,
      self: getSelf()
    };
  }
})(window, window.angular);
