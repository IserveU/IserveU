'use strict';
(function(window, angular, undefined) {

  angular
    .module('iserveu')
    .service('alloyService', ['utils', alloyService]);

  function alloyService(utils) {

    var toolbarPresets = {
      simple: {
        toolbars: {
          styles: {
            selections: [{
              name: 'text',
              buttons: ['bold', 'italic', 'underline', 'link', 'removeFormat'],
              test: AlloyEditor.SelectionTest.text,
              tabIndex: 1
            }]
          }
        }
      },

      editor: {
        removePlugins: 'ae_addimages',
        startupFocus: true,
        toolbars: {
          add: { buttons: ['hline', 'embed'], tabIndex: 2 },
          styles: {
            selections: [{
              name: 'text',
              buttons: [{
                name: 'styles',
                cfg: {
                  styles: [
                    {
                      name: 'Heading 2',
                      style: { element: 'h2' }
                    },
                    {
                      name: 'Heading 3',
                      style: { element: 'h3' }
                    },
                    {
                      name: 'Heading 4',
                      style: { element: 'h4' }
                    },
                    {
                      name: 'Heading 5',
                      style: { element: 'h5' }
                    },
                    {
                      name: 'Heading 6',
                      style: { element: 'h6' }
                    }]
                }
              }, 'bold', 'italic', 'quote', 'link', 'indentBlock',
            'outdentBlock', 'ol', 'ul', 'underline', 'paragraphLeft',
            'paragraphCenter', 'paragraphRight', 'removeFormat'],
              test: AlloyEditor.SelectionTest.text,
              tabIndex: -1
            },
              {
                name: 'image',
                buttons: ['imageLeft', 'imageCenter', 'imageRight'],
                test: AlloyEditor.SelectionTest.image
              }]
          }
        }
      }
    };

    function getToolbar(toolbarRequest) {
      // check if toolbarRequest is set
      if (!toolbarRequest || (angular.isString(toolbarRequest)
        && toolbarRequest.toLowerCase() === 'default')) {
        return {};
      }
      // check if is string
      if (angular.isString(toolbarRequest)) {
          // pulls from a predefined, oft used presets
        return toolbarPresets[toolbarRequest];
      }

      if (angular.isObject(toolbarRequest)) {
        return toolbarRequest;
      }
    }

    return {
      getToolbar: getToolbar
    };

  }

})(window, window.angular);
