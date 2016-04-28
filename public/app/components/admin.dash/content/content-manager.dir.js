(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('contentManager', [
			'pageObj', 'settings', 'ToastMessage',
			contentManager]);

	/** @ngInject */
	function contentManager(pageObj, settings, ToastMessage) {

		function contentController() {

			this.pages = pageObj;
			this.service = settings;
			this.settings = settings.getData();

			this.deletePage = function(slug) {
				ToastMessage.destroyThis("page", function() {
					pageObj.delete(slug);
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