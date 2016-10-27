'use strict';
(function(window, angular, undefined) {

	angular
		.module('iserveu')
		.directive('contentManager', [
			'$rootScope',
			'pageService',
			'settings',
			'ToastMessage',
			'Palette',
			contentManager]);

	/** @ngInject */
	function contentManager($rootScope, pageService, settings, ToastMessage, Palette) {

		function contentController() {
			this.palette = new Palette($rootScope.theme.colors);
			this.service = settings;
		}

		return {
			replace: true,
			controller: contentController,
			controllerAs: 'content',
			templateUrl: 'app/components/admin.dash/content/content-manager.tpl.html',
		}
	}
})(window, window.angular);