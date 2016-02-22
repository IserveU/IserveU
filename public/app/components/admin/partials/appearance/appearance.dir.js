(function(){
	'use strict';

	angular
		.module('iserveu')
		.directive('appearanceManager', appearance);

	function appearance($timeout, appearanceService, refreshLocalStorage, settings, ToastMessage) {

		function appearanceController() {

			var settingsData = JSON.parse(localStorage.getItem('settings'));

			var vm = this;

			this.settings = settings;

			vm.theme = settingsData.theme;
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
		
			function assignThemePalette(array, type) {

				for(var i in array)
					appearanceService.assignHueColors(array, i, vm.theme[type]);

				$timeout(function(){ 
					settings.saveArray('theme.'+type, vm.theme[type] );
				}, 400); 

				return true;
			};

			// TODO: make into a service singleton
			this.save = function(type) {
				if(type === 'palette') {
					this.accent.warning = this.accent.hue_one;
					assignThemePalette(this.accent, 'accent');
					$timeout(function(){
						assignThemePalette(this.primary, 'primary');
					}, 100);
				} 
				else if (type === 'site') 
					settings.saveArray('site', settingsData.site);					
				else if (type === 'favicon')
					settings.saveArray('theme.favicon', this.favicon.filename);	
				else if (type === 'logo')
					settings.saveArray('theme.logo', JSON.parse(this.logo).filename);	

				
				$timeout(function() {
					ToastMessage.reload();
				}, 2000);

			};

		}


		return {
			controller: appearanceController,
			controllerAs: 'appearance',
			templateUrl: 'app/components/admin/partials/appearance/appearance.tpl.html'
		}



	}



})();