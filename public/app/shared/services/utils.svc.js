'use strict';
(function(window, angular, undefined) {

  angular
    .module('iserveu')
    .factory('utils', ['$filter', '$interval', '$timeout', utils]);

  function utils($filter, $interval, $timeout) {

    function capitalize(string) {
      return string.charAt(0).toUpperCase() + string.slice(1);
    }

    // @ http://stackoverflow.com/questions/22898927/
    // injecting-scope-into-an-angular-service-function
    function clearArray(array) {
      return array.splice(0, array.length);
    }

    function count(objectArray) {
      if (objectArray instanceof Object) {
        return Object.keys(objectArray).length;
      }
    }

    var date = {
      stringify: function(date) {
        if (date instanceof Date)
          return $filter('date')(date, 'yyyy-MM-dd HH:mm:ss');
        return this.parse(date);
      },
      parse: function(date) {
        return $filter('date')((new Date(date)), 'yyyy-MM-dd HH:mm:ss');
      }
    };

    function isElementInViewport(el) {
      if (nullOrUndefined(el)) {
        return false;
      }

      var rect = el.getBoundingClientRect();

      return (
        rect.top >= 0 &&
        rect.left >= 0 &&
        rect.bottom <= (window.innerHeight
          || document.documentElement.clientHeight) &&
        rect.right <= (window.innerWidth
          || document.documentElement.clientWidth)
      );
    }

    function nullOrUndefined(val) {
      return val === null || angular.isUndefined(val);
    }

    function objectIsEmpty(obj) {
      if (!obj) {
        return false;
      }
      return Object.keys(obj).length === 0;
    }

    function parseStringToObject() {
      return function(json_string, obj, path) {
        var pars_str = JSON.parse(json_string);
        return obj === path ? pars_str[path] : pars_str;
      };
    }

    function toTitleCase(str) {
      return str.replace(/\w\S*/g, function(txt) {
        return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
      });
    }

    function transformObjectToArray(obj) {
      var tmp = [];
      angular.forEach(obj, function(el, key) {
        tmp.push(el);
      });
      return tmp;
    }

    function waitUntil(tryMethod, doMethod, waitTime) {

      waitTime = waitTime || 7000;

      var waitUntil = $interval(function() {
        if (tryMethod()) {
          doMethod();
          $interval.cancel(waitUntil);
        }
      }, 100, (waitTime / 100));
    }
    
    
    function getNestedWithKey(obj, key) {
      //ES2015 key.split('.').reduce((acc, part) => acc && acc[part], obj)
      var arr = key.split('.');
      while (arr.length && (obj = obj[arr.shift()]));
      return obj;
    }
    
    


    function getUrlParameter(param, dummyPath) {
        var sPageURL = dummyPath || window.location.search.substring(1),
            sURLVariables = sPageURL.split(/[&||?]/),
            res;

        for (var i = 0; i < sURLVariables.length; i += 1) {
            var paramName = sURLVariables[i],
                sParameterName = (paramName || '').split('=');

            if (sParameterName[0] === param) {
                res = sParameterName[1];
            }
        }

        return res;
    }

    return {
      getUrlParameter: getUrlParameter,
      capitalize: capitalize,
      clearArray: clearArray,
      count: count,
      date: date,
      isElementInViewport: isElementInViewport,
      parseStringToObject: parseStringToObject,
      nullOrUndefined: nullOrUndefined,
      objectIsEmpty: objectIsEmpty,
      toTitleCase: toTitleCase,
      transformObjectToArray: transformObjectToArray,
      waitUntil: waitUntil,
      getNestedWithKey: getNestedWithKey
    };
  }

})(window, window.angular);
