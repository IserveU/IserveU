'use strict';
(function(window, angular, undefined) {

  angular
    .module('app.widgets')
    .factory('regexService', ['$http', regexService]);

  function regexService($http) {

    var transformedObj = {
      html: '',
      transformed: false
    };

    var regexSupplement = {
      bold: {
        regex: /<b[^>]*id="[^>].*?>|<\/b[^>]*>/gi,
        replaceWith: ''
      }
    };

    var regexContainer = {
      // img: {
      //     regex: /<img[^>]*>/gi,
      //     replaceWith: ''
      // },
      gdocs: {
        regex: /<b[^>]*id="docs\-internal\-guid\-[^>].*?>(.*?)<\/b[^>]*>/gi,

        replaceWith: function(html) {
          transformedObj.html = html;
          return transformContent(regexSupplement.bold.regex,
            regexSupplement.bold.replaceWith);
        }
      },

      mdash: {
        regex: /-- /g,
        replaceWith: '&mdash;'
      },
      _mdash: {
        regex: /--&nbsp;/g,
        replaceWith: '&mdash;'
      }
    };

    function replaceContent(html) {
      if (!html) {
        return html;
      }

      transformedObj.html = html;
      transformedObj.transformed = false;

      angular.forEach(regexContainer, function(value, key) {
        transformedObj.html = transformContent(value.regex,
            value.replaceWith);
      });

      return transformedObj;
    }

    function transformContent(regex, replaceWith) {
      if (!transformedObj)
        return false;

      var transformedHtml = transformedObj.html.replace(regex,
        function(nextHtml) {
          transformedObj.transformed = true;
          if (typeof replaceWith === 'function') {
            return replaceWith(nextHtml);
          } else {
            return replaceWith;
          }
        });
      return transformedHtml;
    }

    return {
      replaceContent: replaceContent
    };
  }
})(window, window.angular);
