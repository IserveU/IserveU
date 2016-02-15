(function() {

	'use strict';

	angular
		.module('iserveu')
		.service('appearanceService', appearanceService);

	function appearanceService(settings) {

		/*	 
		*	Accessible to be used as a singleton.
		*/
		this.assign = assign;

		/**
		*	Because mdThemingProvider accepts a large pallette of colors,
		*	but realistically we only want 3 colors for our pallette 
		*	on the site's content and as part of the UI configuration
		* 	for the user. This function parses and pushes these values
		* 	to settings API.
		*/
		this.assignHueColors = function(array, key, type) {

			var name = 'theme.'+type+'.', val = array[key];

			switch (key) {
				case 'hue_one': 
					assign( name + 50, val.substr(1) );
					setHue(100, 300, val.substr(1), type);
					break;
				case 'hue_two':
					setHue(400, 600, val.substr(1), type);
					break;
				case 'hue_three':
					setHue(700, 900, val.substr(1), type);
					break;
				case 'warning':
					setHue(100, 700, val.substr(1), type, true);
					break;
				default: 
					assign( name + 'contrastDefaultColor', array[key] );
					break;
			};

		};

		/*	
		*	Sets the hues to fill the pallette given by mdThemingProvider.
		*
		*/
		function setHue(min, max, val, type, prefix)
		{
			for(var hue = min; hue <= max; hue = hue + 100) {
				
				var name = 'theme.'+type+'.';

				if(prefix) {
					hue = hue == 200 ? 400 : ( hue == 400 ? 700 : hue);
					name = name + 'A';
				}

				console.log('name: ' + name + hue + '; value: ' + val );

				assign( (name+hue), val );
			}
		}

		/*	
		*	Arranges data into an array to be saved to the api.
		*
		*/
		function assign(name, value) {

			if (!value)
				return 0;

			if (value.filename)
				value = value.filename;

			settings.save({
				name: name, 
				value: value
			});
		}



	}


})();