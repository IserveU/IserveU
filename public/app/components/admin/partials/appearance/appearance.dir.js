(function(){
	'use strict';

	angular
		.module('iserveu')
		.directive('appearanceManager', appearance);

	function appearance($timeout, appearanceService, refreshLocalStorage, settings, ToastMessage) {

		function appearanceController() {

			var settingsData = JSON.parse(localStorage.getItem('settings'));

			var vm = this;

			vm.settings = settings.getData();

			vm.theme = settingsData.theme;
			vm.themeSelect = settingsData.theme.name;
			vm.site = settingsData.site;
			vm.logo = null;
			vm.favicon = null;

			vm.primary = {
				hue_one: '#'+vm.theme.primary['50'],
				hue_two: '#'+vm.theme.primary['400'],
				hue_three: '#'+vm.theme.primary['700'],
				warning: '#'+vm.theme.primary['A700'],
				contrast: vm.theme.primary['contrastDefaultColor']
			};

			vm.accent = {
				hue_one: '#'+vm.theme.accent['50'],
				hue_two: '#'+vm.theme.accent['400'],
				hue_three: '#'+vm.theme.accent['700'],
				contrast: vm.theme.accent['contrastDefaultColor']
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
			vm.save = function(type) {
				if(type === 'palette') {
					vm.accent.warning = vm.accent.hue_one;
					assignThemePalette(vm.accent, 'accent');
					assignThemePalette(vm.primary, 'primary');
				} 
				else if (type === 'site') 
					settings.saveArray('site', settingsData.site);					
				else if (type === 'favicon')
					settings.saveArray('theme.favicon', vm.favicon.filename);	
				else if (type === 'logo')
					settings.saveArray('theme.logo', JSON.parse(vm.logo).filename);	
				
			};

		}


		function appearanceLink(scope, el, attrs) {

			scope.$watch(
				'appearance.settings.saving',
				function redirect(newValue, oldValue){
					if(newValue == false && oldValue == true)
						ToastMessage.reload();
				});

		}


		return {
			controller: appearanceController,
			controllerAs: 'appearance',
			link: appearanceLink,
			templateUrl: 'app/components/admin/partials/appearance/appearance.tpl.html'
		}



	}



})();