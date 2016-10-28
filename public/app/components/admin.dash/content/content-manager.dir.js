'use strict';
(function(window, angular, undefined) {

	angular
		.module('iserveu')
		.directive('contentManager', [
			'$rootScope',
			'settings',
			'Palette',
			contentManager]);

	function contentManager($rootScope, settings, Palette) {

		function contentController() {

			this.palette = new Palette($rootScope.theme.colors);
			this.service = settings;

			this.materialPalette = false;
			this.customPalette = false;

			this.palettes = {
				custom: false,
				material: false
			};

			this.showSitename = false;
			this.showSocialmedia = false;
			this.showTerms = false;
			this.showTheme = false;

			this.saveMaterialPalette = function(primary, accent) {
				var palette = {
					primary: primary,
					accent: accent
				};
				this.service.saveTypeOf('theme.colors', palette);
			};

			this.saveSocialMedia = function() {

				var socialMedia = {
					address: $rootScope.settingsGlobal.site.address,
					twitter: $rootScope.settingsGlobal.site.twitter,
					facebook: $rootScope.settingsGlobal.site.facebook
				};

				this.service.saveTypeOf('site', socialMedia);
			};

			this.toggleMaterialPalette = function() {
				this.materialPalette = !this.materialPalette;
				this.customPalette = false;
			};

			this.togglePalette = function(type) {
				this.palettes[type] = !this.palettes[type];
				if (type === 'custom') {
					this.palettes.material = false;
				} else
					this.palettes.custom = false;
			};

			this.toggleCustomPalette = function() {
				this.customPalette = !this.customPalette;
				this.materialPalette = false;
			};

			this.toggleSitename = function() {
				this.showSitename = !this.showSitename;
			};

			this.toggleSocialmedia = function() {
				this.showSocialmedia = !this.showSocialmedia;
			};

			this.toggleTerms = function() {
				this.showTerms = !this.showTerms;
			};

			this.toggleTheme = function() {
				this.showTheme = !this.showTheme;
			};

		}

		return {
      restrict: 'EA',
			controller: contentController,
			controllerAs: 'contentManager',
			templateUrl: 'app/components/admin.dash/content/content-manager.tpl.html'
		}
	}
})(window, window.angular);