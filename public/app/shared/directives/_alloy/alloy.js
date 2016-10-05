// (function() {

// 'use strict';

// angular
// .module('iserveu')
// .directive('alloyEditor', ['$timeout', 'alloyService', 'debouncer', alloyEditor]);

//     function alloyEditor($timeout, alloyService, debouncer) {

//         function alloyLink(scope, el, attrs, ngModel) {

//             var REGEX_IMG = /<img[^>]*>/gi;
//             var REGEX_GDOCS = /<b[^>]*id="docs\-internal\-guid\-[^>].*?>(.*?)<\/b[^>]*>/gi;
//             var REGEX_BOLD = /<b[^>]*id="[^>].*?>|<\/b[^>]*>/gi;
//             var debounce = new debouncer.Debounce();

//             $timeout(function() {

//                 var alloyEditor = AlloyEditor.editable(attrs.id, { startupFocus : true });

//                 if (!ngModel) {
//                     return;
//                 }

//                 var nativeEditor = alloyEditor.get('nativeEditor');

//                 function replaceGDocs(html) {
//                     var transformedHtml = html.replace(REGEX_GDOCS, function(gDocs) {
//                         var transformedString = gDocs.replace(REGEX_BOLD, function() {
//                             debounce.Invoke(function(){
//                                 refreshView(transformedHtml);
//                             }, 20, false);
//                             return '';
//                         })
//                         return transformedString;
//                     });
//                     return transformedHtml;
//                 }

//                 function refreshView(content) {
//                     nativeEditor.setData('', function() {
//                         nativeEditor.focus();
//                         nativeEditor.insertHtml(content);
//                     });
//                 }

//                 nativeEditor.on('pasteState', function(_el) {
//                     var content = nativeEditor.getData();

//                     // console.log(content);

//                     // if(!content || content == '') {
//                     //     nativeEditor.insertHtml('<p style="opacity: .7">Click to start writing ... </p>');
//                     // } else {
//                     content = content.replace(/--&nbsp;/g, '&mdash;');
//                     content = content.replace(/-- /g, '&mdash;');
//                     content = replaceGDocs(content);
//                     // }
//                     // console.log(content);

//                     ngModel.$setViewValue(content);

//                     if(content.includes('&mdash;')) {
//                         debounce.Invoke(function(){
//                             refreshView(content);
//                         }, 20, false);
//                     }
//                 });


//                 ngModel.$render = function(value) {
//                     nativeEditor.setData(ngModel.$viewValue, function() {
//                         nativeEditor.focus();
//                     });
//                 };

//             }, 10);
//         }

//     return {
//         link: alloyLink,
//         restrict: 'A',
//         require: '?ngModel',
//         scope: false
//     };
// };

// })();
