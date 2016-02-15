(function(){
	'use strict';

	angular
		.module('iserveu')
		.directive('appearanceManager', appearance);

	function appearance($timeout, appearanceService, refreshLocalStorage, settings) {

		function appearanceController() {

			var settingsData = JSON.parse(localStorage.getItem('settings'));

			this.theme = settingsData.theme;
			this.themeSelect = settingsData.theme.name;
			this.site = settingsData.site;
			this.logo = null;
			this.favicon = null;

			this.primary = {
				hue_one: '#'+this.theme.primary['50'],
				hue_two: '#'+this.theme.primary['400'],
				hue_three: '#'+this.theme.primary['700'],
				warning: '#'+this.theme.primary['A700'],
				contrast: this.theme.primary['contrastDefaultColor']
			};

			this.accent = {
				hue_one: '#'+this.theme.accent['50'],
				hue_two: '#'+this.theme.accent['400'],
				hue_three: '#'+this.theme.accent['700'],
				contrast: this.theme.accent['contrastDefaultColor']
			};
		
			function assignSettingValue(array, type) {
				for(var i in array)
					appearanceService.assignHueColors(array, i, type);

				return true;
			};

			this.saveAppearanceSettings = function() {
				if(this.themeSelect !== this.theme.name){
					
					settings.saveArray('theme.name', this.themename);

					assignSettingValue(this.primary, 'primary');

					this.accent.warning = this.accent.hue_one;

					assignSettingValue(this.accent, 'accent');
				}

				settings.saveArray('site.name', this.site.name);

				if(this.favicon) settings.saveArray('theme.favicon', this.favicon.filename);	
				if(this.logo) settings.saveArray('theme.logo', this.logo.filename);	

			};


			function check() {
				console.log('really is it async?');
			}

		}


		return {
			controller: appearanceController,
			controllerAs: 'ctrl',
			templateUrl: 'app/components/admin/partials/appearance/appearance.tpl.html'
		}



	}



})();