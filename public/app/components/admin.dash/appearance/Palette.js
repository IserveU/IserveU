(function() {

	'use strict';

	angular
		.module('iserveu')
		.service('Palette', Palette);

	/** @ngInject */
	function Palette() {


		function Palette(theme){
			// 'Original' is required for the parsing. It reuses the object array given 
			// from settings. If you were to create an object array, it creates
			// a 901 length array because of the key identifiers. 
			this.original = theme || null; 
			this.primary = {
				hue_one: '#'+theme.primary['50'] || null,
				hue_two: '#'+theme.primary['400'] || null,
				hue_three: '#'+theme.primary['700'] || null,
				warning: '#'+theme.primary['A700'] || null,
				contrast: theme.primary['contrastDefaultColor'] || null
			};
			this.accent = {
				hue_one: '#'+theme.accent['50'] || null,
				hue_two: '#'+theme.accent['400'] || null,
				hue_three: '#'+theme.accent['700'] || null,
				contrast: theme.accent['contrastDefaultColor'] || null
			};
		}

		/**
		*	Parses the palette arrays and passes to assignHueColors.
		*/
		Palette.prototype.assignThemePalette = function(palette) {

			var result = {};

			palette.accent.warning = palette.accent.hue_one;

			for(var i in palette) 
				if( i !== 'original')
				result[i] = assignHueColors( palette[i], palette.original[i] );
				
			return result;
		};

		/**
		*	Because mdThemingProvider accepts a large palette of colors,
		*	but realistically we only want 3 colors for our palette 
		*	on the site's content and as part of the UI configuration
		* 	for the user. This function parses and pushes these values
		* 	to settings API.
		*/
		function assignHueColors(array, palette) {


			for(var i in array) {

				var val = array[i];

				switch (i) {
					case 'hue_one': 
						palette[ '50' ] = val.substr(1);
						setHue(100, 300, val.substr(1), palette);
						break;
					case 'hue_two':
						setHue(400, 600, val.substr(1), palette);
						break;
					case 'hue_three':
						setHue(700, 900, val.substr(1), palette);
						break;
					case 'warning':
						setHue(100, 700, val.substr(1), palette, true);
						break;
					default:
						palette['contrastDefaultColor'] = val;
						break;
				};
			};

			return palette;
		};

		/*	
		*	Sets the hues to fill the palette given by mdThemingProvider.
		*
		*/
		function setHue(min, max, val, palette, prefix) {
			for(var hue = min; hue <= max; hue = hue + 100) {
				
				if(prefix) {
					hue = hue == 200 ? 400 : ( hue == 400 ? 700 : hue);
					palette['A' + hue ] = val;
				} 
				else palette[ hue ] = val;
			}
		}

		return Palette;

	}


})();