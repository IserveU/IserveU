(function() {

	'use strict';

	angular
		.module('app.admin.dash')
		.service('Palette', ['$mdColorUtil', Palette]);

	/** @ngInject */
	function Palette($mdColorUtil) {


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

			for (var i in palette)
				if (i !== 'original')
					result[i] = assignHueColors(palette[i], palette.original[i]);

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

			var newPalette = angular.copy(palette);

			for (var i in array) {

				if (!array[i])
					return;

				var hex = array[i], keys = [];

				if (hex === 'light' || hex === 'dark') {
					setHue('contrastDefaultColor', hex, palette);
				} else if (hex.charAt(0) !== '#') {
					hex = $mdColorUtil.rgbaToHex(hex);
				}

				// remove the octothorpe
				hex = hex.substr(1);

				switch (i) {
					case 'hue_one':
					  keys = ['50', '100', '200', '300'];
						setHue(keys, hex, palette);
						break;
					case 'hue_two':
						keys = ['400', '500', '600'];
						setHue(keys, hex, palette);
						break;
					case 'hue_three':
						keys = ['700', '800', '900'];
						setHue(keys, hex, palette);
						break;
					case 'warning':
						keys = ['A100', 'A200', 'A400', 'A700'];
						setHue(keys, hex, palette);
						break;
					default:
						break;
				}
			}

			return palette;
		}

		/*
		*	Sets the hues to fill the palette given by mdThemingProvider.
		*
		*/
		function setHue(key, hue, palette) {

			var set = function(_key) {
				if (palette[_key] !== hue) {
					palette[_key] = hue;
				}
				else {
					delete palette[_key];
				}
			};

			if (typeof key === 'string')
				set(key);
			else if (angular.isArray(key)) {
				for (var i in key) {
					if (key[i])
						set(key[i]);
				}
			}
		}

		return Palette;

	}


})();