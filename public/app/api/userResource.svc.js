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
        
        //Probably should make a dates->objects functions
        if(result.date_of_birth ===undefined || 
            result.date_of_birth==="" || result.date_of_birth.includes("-000"))
            {
          result.date_of_birth = null;
        } else {
          result.date_of_birth = new Date(result.date_of_birth);
        }
         

        // probably dont need to update localStorage when getUser for now. 
        // but if needed, please uncomment the following codes for security.
        // if(localStorage.getItem('user') === undefined){
        //     localStorage.setItem('user', JSON.stringify(result));
        // }
        return result;
      }, function(error) {
        return $q.reject(error);
      });
    }

    function getUsers(data) {
      return User.get(data).$promise.then(function(succcess) {
        return succcess;
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
      var oldUser = JSON.parse(localStorage.getItem('user'));
      return User.update({id: id}, data).$promise.then(function(result) {
        // only the current user need his/her localStorage updated, admin dont.
        if(id == oldUser.id){
            localStorage.setItem('user', JSON.stringify(result));
        }
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
      getUsers: getUsers,
      updateUser: updateUser,
      deleteUser: deleteUser,
      storeUser: storeUser,
      self: getSelf()
    };
  }
})(window, window.angular);
