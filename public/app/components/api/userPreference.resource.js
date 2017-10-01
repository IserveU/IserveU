'use strict';
(function(window, angular, undefined) {


  angular
    .module('app.api')
    .factory('UserPreferenceResource', ['$http', '$q', UserPreferenceResource]);

  function UserPreferenceResource($http, $q) {

    function getUserPreferences(slug) {
      return $http.get('api/user/' + slug).then(function(results) {
        return results.data.preferences;
      }, function(error) {
        return $q.reject(error);
      });
    }

    function setUserPreference(data) {
      return $http.post('api/user/' + data.slug + '/setpreference/' + data.key, {value: data.value})
      .then(function(results) {
        return results;
      }, function(error) {
        return $q.reject(error);
      });
    }

    return {
      setUserPreference: setUserPreference,
      getUserPreferences: getUserPreferences
    };
  }
}(window, window.angular));
