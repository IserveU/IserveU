(function() {

	'use strict';

	angular
		.module('iserveu')
		.service('appearanceService', appearanceService);

	function appearanceService(settings) {

		/**
		*	Because mdThemingProvider accepts a large palette of colors,
		*	but realistically we only want 3 colors for our palette 
		*	on the site's content and as part of the UI configuration
		* 	for the user. This function parses and pushes these values
		* 	to settings API.
		*/
		this.assignHueColors = function(array, key, palette) {

			var val = array[key];

			switch (key) {
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

		/*	
		*	Sets the hues to fill the palette given by mdThemingProvider.
		*
		*/
		function setHue(min, max, val, palette, prefix)
		{
			for(var hue = min; hue <= max; hue = hue + 100) {
				
				if(prefix) {
					hue = hue == 200 ? 400 : ( hue == 400 ? 700 : hue);
					palette['A' + hue ] = val;
				} 
				else palette[ hue ] = val;
			}
		}

	}


})();