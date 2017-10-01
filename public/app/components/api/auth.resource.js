'use strict';
(function (window, angular, undefined) {
  angular
    .module('app.api')
    .factory('AuthResource', [
      '$http',
      '$sanitize',
      '$q',
      AuthResource])

  function AuthResource ($http, $sanitize, $q) {
    var login = function (credentials) {
      /** using $auth service from satellizer is deprecated */
      return $http.post('authenticate/', sanitizeCredentials(credentials)).then(function(user) {
        return user
      }, function (error) {
        return $q.reject(error)
      })
    }

    var logout = function () {
      /** deprecated $auth.logout (which only removes token from local storage) */
      return $http.post('authenticate/logout').then(function(result) {
        return result
      }, function(error) {
        return $q.reject(error)
      })
    }

    var register = function (credentials) {
      return $http.post('api/user', sanitizeCredentials(credentials)).then(function (result) {
        return result
      }, function (error) {
        return $q.reject(error)
      })
    }

    var rememberToken = function (rememberToken) {
      return $http.get('authenticate/' + rememberToken).then(function (user) {
        return user
      }, function (error) {
        return $q.reject(error)
      })
    }

    var resetPassword = function (credentials) {
      return $http.post('authenticate/resetpassword', sanitizeCredentials(credentials))
      .then(function (result) {
        return result
      }, function (error) {
        return $q.reject(error)
      })
    }

    /**
     * Sanitize the credential.
     * @param  {object} credentials
     * @return {object} sanitized email, password
     */
    function sanitizeCredentials (credentials) {
      return {
        email: $sanitize(credentials.email),
        password: $sanitize(credentials.password)
      }
    }

    return {
      login: login,
      logout: logout,
      register: register,
      rememberToken: rememberToken,
      resetPassword: resetPassword
    }
  }
})(window, window.angular)
