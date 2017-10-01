// @Deprecated
// (function() {

//  'use strict';

//  angular
//    .module('iserveu')
//    .directive('formatAddress', ['utils', formatAddress]);

//  function formatAddress(utils) {

//    return {
//      require: "ngModel",
//      link: function(scope, element, attrs, ngModelController) {

//            ngModelController.$formatters.push(function(data) {

//              var address = '';

//              if( !data.street_name )
//                return "Enter your address";
//              else if ( !data.unit_number && !data.street_number)
//                address = utils.toTitleCase(data.street_name);
//              else if( !data.unit_number )
//                address = data.street_number + ' '
//                + utils.toTitleCase(data.street_name);
//              else if ( !data.street_number )
//                address = "Unit #" + data.unit_number
//                + ' ' + utils.toTitleCase(data.street_name);
//              else
//            address = data.unit_number + '-'
//            + data.street_number + ' ' + utils.toTitleCase(data.street_name);

//          return address;

//          });
//      }
//    }
//  }

// }());
