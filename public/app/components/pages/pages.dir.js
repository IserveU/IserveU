(function() {


	'use strict';

	angular
		.module('iserveu')
		.directive('pageContent', [
			'$state',
			'$stateParams',
			'pageService',
			'UserbarService',
			'ToastMessage',
		pageContent]);

  	 /** @ngInject */
	function pageContent($state, $stateParams, pageService, UserbarService, ToastMessage) {


		function pageController() {

			this.service = pageService;
			this.loading = "loading";

	/***=== Exports =================================================== */

			this.create  = create;
			this.edit    = edit;
			this.destroy = destroy;

			function create() {
				$state.go('create-page');
			}

			function edit() {
				$state.go('edit-page', {id: pageService.slug});
			}

			function destroy() {
				ToastMessage.destroyThis("page", function() {
					pageService.delete($stateParams.id);
				});
			}

	/***=== Initialization ============================================ */
			(function init() {
				UserbarService.title = pageService.title;
				pageService.initLoad($stateParams.id);
			})();
		}

		return {
			controller: pageController,
			controllerAs: 'page',
			templateUrl: 'app/components/pages/pages.tpl.html'
		}

	}


})();