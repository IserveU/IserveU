(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('contentManager', contentManager);

	/** @ngInject */
	function contentManager($state, pageObj, settings, dropHandler, ToastMessage) {

		function contentController() {

			this.pages = pageObj;
			this.service = settings;
			this.settings = settings.getData();
			this.dropHandler = 	dropHandler;

			this.deletePage = function(slug) {
				ToastMessage.destroyThis("page", function() {
					pageObj.delete(slug);
				});
			};

		};


		return {
			controller: contentController,
			controllerAs: 'content',
			templateUrl: 'app/components/admin/partials/content/content-manager.tpl.html',
		}


	}


})();