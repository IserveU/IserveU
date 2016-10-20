'use strict';
(function(window, angular, undefined) {

  angular
    .module('iserveu')
    .directive('alloyEditor', [
      '$rootScope',
      '$timeout',
      '$state',
      '$stateParams',
      'alloyService',
      'regexService',
      'debouncer',
      'CSRF_TOKEN',
      alloyEditor]);

  function alloyEditor($rootScope, $timeout, $state, $stateParams,
    alloyService, regexService, debouncer, CSRF_TOKEN) {

    function alloyLink(scope, el, attrs, ngModel) {

      scope.uploader = {};

      function initAlloy() {

        var alloyEditor = AlloyEditor.editable(attrs.id,
            alloyService.getToolbar(attrs.alloyToolbar));

        if (!ngModel) {
          return false;
        }

        var nativeEditor = alloyEditor.get('nativeEditor');

        /* ===========================================================
        |
        |   Not the best way to do this. But it works. setData() resets
        |   the cursor back to the beginning of the textarea. It would
        |   be worthwhile to reset it back to where it was. That being said
        |   I wasn't able to get it running in the method. So this is
        |   a little hack.
        |
        | ===========================================================*/
        function refreshView(content) {
          nativeEditor.setData('', function() {
            nativeEditor.focus();
            nativeEditor.insertHtml(content);
          });
        }

        nativeEditor.on('pasteState', function() {
          var content = nativeEditor.getData(), transformedHtml;

          if (typeof content !== undefined) {
            transformedHtml = regexService.replaceContent(content);
          }

          content = transformedHtml.html;
          ngModel.$setViewValue(content);

          if (transformedHtml.transformed) {
            var debounce = new debouncer.Debounce();
            debounce.Invoke(function() {
              refreshView(content);
            }, 20, false);
          }
        });

        nativeEditor.on('imageAdd', function(event) {
          var item_id = $stateParams.slug || $stateParams.id;
          var parent_name = $state.current.name;

          parent_name = parent_name.split('-')[1];

          if (parent_name == 'home')
            parent_name = 'page';

          var endpoint = '/api/' + parent_name + '/' + item_id + '/file/';

          scope.$flow.opts.target = endpoint;

          scope.$flow.opts.headers = {
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'Authorization': 'Bearer ' + localStorage.getItem('api_token')
          };
          scope.$flow.opts.testChunks = false;
          scope.$flow.addFile(event.data.file, event);
          scope.$flow.upload();

          scope.$flow.on('fileSuccess', function(file, message) {
            var data = JSON.parse(message);
            event.data.el.setAttribute('src',
              endpoint + data.slug + '/resize/1920');
          });

          scope.$flow.on('fileError', function(file, message) {
            event.data.el.remove();
          });
        });

        /* ==========================================================
        |
        |   The $render function in the ngModel controller is
        |   called when the model is changed and
        |   the view is to be updated.
        |   A good description and explanation on this stackoverflow:
        |   http://stackoverflow.com/questions/21083543/
        |   when-ngmodels-render-is-called-in-angularjs
        |
        | ========================================================= */

        ngModel.$render = function(value) {
          var model = ngModel.$viewValue;
          nativeEditor.setData(model, function() {
            nativeEditor.focus();
          });
        };
      }


    /* ==========================================================
    |
    |   @timeout: Attaching a custom directive invokes an error
    |   with alloyEditor. The editor is instantiated before the directive
    |   it is attached to, so must offset very slightly.
    |
    | ========================================================= */
      $timeout(function() {
        initAlloy();
      }, 20);
    }

    return {
      link: alloyLink,
      restrict: 'EA',
      require: 'ngModel',
      scope: true
    };
  }

})(window, window.angular);
