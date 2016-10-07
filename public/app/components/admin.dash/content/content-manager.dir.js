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



		}


		return {
			replace: true,
			controller: contentController,
			controllerAs: 'content',
			templateUrl: 'app/components/admin.dash/content/content-manager.tpl.html',
		}


	}


})();