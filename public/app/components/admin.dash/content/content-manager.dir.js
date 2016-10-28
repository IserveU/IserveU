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

			this.showSitename = false;
			this.showSocialmedia = false;
			this.showTerms = false;
			this.showTheme = false;

			this.toggleSitename = function() {
				this.toggleSitename = !this.toggleSitename;
			};

			this.toggleSocialmedia = function() {
				this.showSocialmedia = !this.showSocialmedia;
			};

			this.toggleTerms = function() {
				this.showTerms = !this.showTerms;
			};

			this.toggleTheme = function() {
				this.showTheme = this.showTheme;
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