(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('contentManager', [
			'pageService', 'settings', 'ToastMessage',
			contentManager]);

	/** @ngInject */
	function contentManager(pageService, settings, ToastMessage) {

		function contentController() {

			this.pages = pageService;
			this.service = settings;
			this.settings = settings.getData();

			this.deletePage = function(slug) {
				ToastMessage.destroyThis("page", function() {
					pageService.delete(slug);
				});
			};

		};


		return {
			replace: true,
			controller: contentController,
			controllerAs: 'content',
			templateUrl: 'app/components/admin.dash/content/content-manager.tpl.html',
		}


	}


})();