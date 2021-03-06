'use strict';
(function(window, angular, undefined) {

  angular
    .module('iserveu')
    .factory('fileService', ['$http', fileService]);


  function fileService($http) {

    var upload = function(file) {
      var fd = new FormData();

      fd.append('file', file);

      return $http.post('file', fd, {
        transformRequest: angular.identity,
        headers: {
          'Content-type': undefined
        }
      }).success(function(r) {
        return r;
      }).error(function(e) {
        return e;
      });
    };

    var get = function(file_id) {
      return $http.get('file/' + file_id, {
        transformRequest: angular.identity,
        headers: {
          'Content-type': undefined
        }
      }).success(function(r) {
        return r;
      }).error(function(e) {
        return e;
      });
    };

    return {
      upload: upload,
      get: get
    };

  }

})(window, window.angular);
