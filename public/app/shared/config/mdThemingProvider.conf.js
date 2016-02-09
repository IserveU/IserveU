(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.config(

	function($mdThemingProvider){

		var theme = JSON.parse(localStorage.getItem('settings')).theme;

		$mdThemingProvider.definePalette('primary', {
	        '50': theme.primary['50'],
	        '100': theme.primary['100'],
	        '200': theme.primary['200'],
	        '300': theme.primary['300'],
	        '400': theme.primary['400'],
	        '500': theme.primary['500'],
	        '600': theme.primary['600'],
	        '700': theme.primary['700'],
	        '800': theme.primary['800'],
	        '900': theme.primary['900'],
	        'A100': theme.primary['A100'],
	        'A200': theme.primary['A200'],
	        'A400': theme.primary['A400'],
	        'A700': theme.primary['A700'],
	        'contrastDefaultColor': theme.primary['contrastDefaultColor'],    // whether, by default, text (contrast)
	        'contrastDarkColors': theme.primary['A700'],
	        'contrastLightColors': 'dark' // could also specify this if default was 'dark'
        });

		$mdThemingProvider.definePalette('accent', {
	        '50': theme.accent['50'],
	        '100': theme.accent['100'],
	        '200': theme.accent['200'],
	        '300': theme.accent['300'],
	        '400': theme.accent['400'],
	        '500': theme.accent['500'],
	        '600': theme.accent['600'],
	        '700': theme.accent['700'],
	        '800': theme.accent['800'],
	        '900': theme.accent['900'],
	        'A100': theme.accent['A100'],
	        'A200': theme.accent['A200'],
	        'A400': theme.accent['A400'],
	        'A700': theme.accent['A700'],
	        'contrastDefaultColor': theme.accent['contrastDefaultColor'],    // whether, by default, text (contrast)
	        'contrastDarkColors': theme.accent['A700'],
	        'contrastLightColors': 'dark' // could also specify this if default was 'dark'
        });

  	    $mdThemingProvider.theme('default')
            .primaryPalette('primary', {
            	'default': '400',
            	'hue-1': '50',
            	'hue-2': '400',
            	'hue-3': '700'
            })
            .accentPalette('accent', {
            	'default': '400',
            	'hue-1': '50',
            	'hue-2': '400',
            	'hue-3': '700'
            });

	});

})();