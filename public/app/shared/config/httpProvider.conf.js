'use strict';
(function(window, angular, undefined) {

  angular
    .module('iserveu')
    .config(['$httpProvider',

  function($httpProvider) {

    // for AJAX
    $httpProvider.defaults.headers
      .common['X-Requested-With'] = 'XMLHttpRequest';

    $httpProvider.interceptors.push(['$q', '$injector',
      function($q, $injector) {
        return {
          'request': function(config) {
            // LARAVEL HACK
            if (config.method === 'PATCH' || config.method === 'PUT') {
              config.transformRequest = transformToFormData;
              config.headers['Content-Type'] = undefined;
              config.headers['X-HTTP-Method-Override'] = 'PATCH';
              config.method = 'POST';
            }

            // probably not the best place to do this
            var api_token = localStorage.getItem('api_token');
            config.headers['Authorization'] = 'Bearer ' + api_token;

            return config || $q.when(config);
          },
          'response': function(config) {
            return config || $q.when(config);
          },
          'responseError': function(config) {

            var ERROR_CODES = [400, 405, 500];
            var toast = $injector.get('ToastMessage');
            var auth = $injector.get('Authorizer');

            if (ERROR_CODES.indexOf(config.status) >= 0 &&
                auth.canAccess('administrate-permission')) {
              // TODO: only show for admins
              toast.report_error(config.data);
            } else if (config.status === 401 && config.statusText === 'Unauthorized') {
              var loginService = $injector.get('loginService');
              loginService.clearCredentials(true);
            } else if (config.status === 401) {
              toast.mustBeLoggedIn('to perform this action.');
            } else if (config.status === 404) {
              // var state = $injector.get('$state');
              // toast.customFunction('Page not found', 'Go home',
              //   function() {state.go('home');}, false);
            }
            // else if(config.status === 403) { // currently too many 403s..
            //  toast.simple('You do not have permission
            //  to perform this action.');
            // }

            return $q.reject(config);
          }
        };
      }]);

    /**
    * Transforms object array into FormData type to post to API.
    */
    function transformToFormData(data, getHeaders) {
      var _fd = new FormData();
      angular.forEach(data, function checkArrayFirstLevel(val, key) {
        if (val === null ||
           val === '' ||
           angular.isUndefined(val) ||
           key.charAt(0) === '$') {

          return;
        }

        if (typeof val === 'object' && Object.keys(val).length !== 0) {
          transformObjectToFormData(_fd, val, key);
        } else if (key.charAt(0) !== '$' &&
          typeof val === 'string' ||
          typeof val === 'number' ||
          typeof val === 'boolean' ||
          val instanceof File) {

          _fd.append(key, val);
        }
      });

      function transformObjectToFormData(fd, obj, key) {
        angular.forEach(obj, function checkDeepArray(i, e) {
          if (typeof i === 'object') {
            if (typeof e === 'string' && e.charAt(0) === '$')
              return;

            var t = key + '[' + e + ']';
            if (i instanceof File) {
              fd.append(t, i);
              // checks for primitive number and string
              // that does not begin with $
            } else if (Array.isArray(e) ||
              typeof e === 'object' ||
              typeof e === 'number' ||
              typeof e === 'boolean' ||
              (typeof e === 'string' && e.charAt(0) !== '$'))
              transformObjectToFormData(fd, i, t);

          } else if (!angular.isUndefined(i))
            fd.append(key + '[' + e + ']', i);
        });
      }
      return _fd;
    }
  }]);
})(window, window.angular);
