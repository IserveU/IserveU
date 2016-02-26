(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('palette', paletteObj);

	/** @ngInject */
	function paletteObj(settings) {

		var settings = settings.getData();

		/**
		*	UI models for theming palette.
		*/
		return {
			// 'Blank' is required for the parsing. It reuses the object array given 
			// from settings. If you were to create an object array, it creates
			// a 901 length array because of the key identifiers. 
			blank: settings.theme, 
			primary: {
				hue_one: '#'+settings.theme.primary['50'],
				hue_two: '#'+settings.theme.primary['400'],
				hue_three: '#'+settings.theme.primary['700'],
				warning: '#'+settings.theme.primary['A700'],
				contrast: settings.theme.primary['contrastDefaultColor']
			},
			accent: {
				hue_one: '#'+settings.theme.accent['50'],
				hue_two: '#'+settings.theme.accent['400'],
				hue_three: '#'+settings.theme.accent['700'],
				contrast: settings.theme.accent['contrastDefaultColor']
			}
		};	
	}


})();