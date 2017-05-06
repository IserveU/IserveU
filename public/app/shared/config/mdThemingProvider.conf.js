'use strict';
(function(window, angular, undefined) {

  angular
    .module('iserveu')
    .config(['$mdAriaProvider', function($mdAriaProvider) {
      // Globally disables all ARIA warnings.
      $mdAriaProvider.disableWarnings();
    }])
    .config(['$mdThemingProvider', 'SETTINGS_JSON',
      function($mdThemingProvider, SETTINGS_JSON) {
        var name = SETTINGS_JSON.theme.name || 'default';

        var theme = chooseTheme();

        function chooseTheme() {
          //customTheme mode on
          if (SETTINGS_JSON.theme.customTheme === 1) {
            return SETTINGS_JSON.theme.colors;
          } else return SETTINGS_JSON.theme.predefined;
        }

        var palettes = ['primary', 'accent'];

        definePalettes();



        function definePalettes() {
          for (var i in palettes) {
            if (angular.isObject(theme[palettes[i]])) {
              $mdThemingProvider.definePalette(palettes[i], {
                '50': theme[palettes[i]]['50'],
                '100': theme[palettes[i]]['100'],
                '200': theme[palettes[i]]['200'],
                '300': theme[palettes[i]]['300'],
                '400': theme[palettes[i]]['400'],
                '500': theme[palettes[i]]['500'],
                '600': theme[palettes[i]]['600'],
                '700': theme[palettes[i]]['700'],
                '800': theme[palettes[i]]['800'],
                '900': theme[palettes[i]]['900'],
                'A100': theme[palettes[i]]['A100'],
                'A200': theme[palettes[i]]['A200'],
                'A400': theme[palettes[i]]['A400'],
                'A700': theme[palettes[i]]['A700'],
                // whether, by default, text (contrast)
                'contrastDefaultColor': theme[palettes[i]]['contrastDefaultColor'],
                'contrastDarkColors': theme[palettes[i]]['A700'],
                // could also specify this if default was 'dark'
                'contrastLightColors': 'dark'
              });
            } else {
              palettes[i] = theme[palettes[i]];
            }
          }
          setPalette(palettes[0], palettes[1]);
        }


        function setPalette(primary, accent) {
          $mdThemingProvider.theme(name)
            .primaryPalette(primary, {
              'default': '400',
              'hue-1': '50',
              'hue-2': '400',
              'hue-3': '700'
            })
            .accentPalette(accent, {
              'default': '400',
              'hue-1': '50',
              'hue-2': '400',
              'hue-3': '700'
            });
        }

      }
    ]);

})(window, window.angular);