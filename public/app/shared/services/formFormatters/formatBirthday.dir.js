// @deprecated
// (function() {

//  angular
//    .module('iserveu')
//    .directive('formatBirthday', ['$filter', formatBirthday]);

//  function formatBirthday($filter) {

//    return {
//        require: 'ngModel',
//        link: function(scope, element, attrs, ngModelController) {

//        // ngModelController.$parsers.push(function(data) {
//        //  return new Date(data);
//        // });

//            ngModelController.$formatters.push(function(data) {
//          var d = new Date(data);
//          return new Date(d.setTime( d.getTime() +
//          d.getTimezoneOffset()*60000 ));
//          });
//        }
//    };
//  }

// })();
