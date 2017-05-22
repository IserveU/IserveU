'use strict';
(function(window, angular, undefined) {

  angular
    .module('iserveu')
    .factory('authResource', [
      '$auth',
      '$http',
      '$sanitize',
      '$q',
      authResource]);

  function authResource($auth, $http, $sanitize, $q) {
    var login = function(credentials) {
      return $auth.login(sanitizeCredentials(credentials)).then(function(user) {
        return user;
      }, function(error) {
        return $q.reject(error);
      });
    };

    var logout = function() {
      return $auth.logout();
    };

    var register = function(credentials) {
      return $http.post('api/user', credentials).success(function(result) {
        return result;
      }).error(function(error) {
        return error;
      });
    }

    var rememberToken = function(rememberToken) {
      return $http.get('authenticate/' + rememberToken).success(function(user) {
        return user;
      }).error(function(error) {
        return error;
      });
    };

    var resetPassword = function(credentials) {
      return $http.post('authenticate/resetpassword', credentials)
      .success(function(result) {
        return result;
      }).error(function(error) {
        return error;
      });
    };

    /**
     * Sanitize the credential.
     * @param  {object} credentials
     * @return {object} sanitized email, password
     */
    function sanitizeCredentials(credentials) {
      return {
        email: $sanitize(credentials.email),
        password: $sanitize(credentials.password)
      };
    }

    return {
      login: login,
      logout: logout,
      register: register,
      rememberToken: rememberToken,
      resetPassword: resetPassword
    };
  }
})(window, window.angular);
