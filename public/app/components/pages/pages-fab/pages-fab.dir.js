(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('pagesFab', pagesFab);

  	 /** @ngInject */
	function pagesFab($stateParams, pageObj, fabLink, ToastMessage) {

		function pagesFabController() {

			this.pageObj = pageObj;

			this.isOpen = false;

			this.destroy = function () {
				ToastMessage.destroyThis("page", function() {
					pageObj.delete($stateParams.id);
				});
			};
		};

		function pagesFabLink(scope, el, attrs) {
			fabLink(el);
		};

		return {
			controller: pagesFabController,
			controllerAs: 'fab',
			link: pagesFabLink,
			templateUrl: 'app/components/pages/pages-fab/pages-fab.tpl.html'
		}

	}


})();