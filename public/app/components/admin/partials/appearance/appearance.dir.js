(function(){
	'use strict';

	angular
		.module('iserveu')
		.directive('appearanceManager', appearance);

	function appearance($timeout, appearanceService, refreshLocalStorage) {

		function appearanceController() {

			var settings = JSON.parse(localStorage.getItem('settings'));

			this.theme = settings.theme;
			this.themeSelect = settings.theme.name;
			this.site = settings.site;
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
					
					appearanceService.assign('theme.name', this.themename);

					assignSettingValue(this.primary, 'primary');

					this.accent.warning = this.accent.hue_one;

					assignSettingValue(this.accent, 'accent');
				}

				appearanceService.assign('site.name', this.site.name);
				appearanceService.assign('theme.favicon', this.favicon);	
				appearanceService.assign('theme.logo', this.logo);	

				$timeout(function() {
					refreshLocalStorage.init();
				}, 500);
			};

		}


		return {
			controller: appearanceController,
			controllerAs: 'ctrl',
			templateUrl: 'app/components/admin/partials/appearance/appearance.tpl.html'
		}



	}



})();