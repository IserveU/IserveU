'use strict';
(function(window, angular, undefined) {

	angular
		.module('iserveu')
		.directive('contentManager', [
			'$rootScope',
			'settings',
			'Palette',
			'pageService',
			'$timeout',
			contentManager]);

	function contentManager($rootScope, settings, Palette, pageService, $timeout) {

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
			this.showFavicon = false;
			this.showLoginImage = false;
      this.showBackground = false;
			this.showTheme = false;

			this.backgroundFiles = [];
			this.pageService = pageService;

			var self = this;
			this.saveBackground = function(url) {
				if (!url) {
					var file = '/api/page/' + pageService.index[0].slug + '/file/' + this.backgroundFiles.pop() + '/resize/1920';

					self.service.saveTypeOf('theme.background', file);
					$rootScope.settingsGlobal.theme.background = file;
				}

				$timeout(function() {
					self.toggleBackground();
		      document.body.style.backgroundImage = (('url(' +
		      $rootScope.settingsGlobal.theme.background + ')') || '#FBFBFB');
				}, 500);
			};

			this.saveFavicon = function() {
				$timeout(function() {
					self.toggleFavicon();
				}, 2000);
			};

			this.saveLoginImage = function() {
				$timeout(function() {
					self.toggleLoginImage();
				}, 2000);
			};

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

			this.togglePalette = function(type) {
				this.palettes[type] = !this.palettes[type];
				if (type === 'custom') {
					this.palettes.material = false;
				} else
					this.palettes.custom = false;
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

			this.toggleFavicon = function() {
				this.showFavicon = !this.showFavicon;
			};

			this.toggleLoginImage = function() {
				this.showLoginImage = !this.showLoginImage;
			};

	    this.toggleBackground = function() {
	        this.showBackground = !this.showBackground;
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