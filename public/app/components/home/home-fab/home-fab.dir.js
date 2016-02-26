(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('homeFab', homeFab);

	/** @ngInject */
	function homeFab(fabLink) {

		function homeFabController() {
			this.isOpen = false;
		}

		function homeFabLink(scope, el, attrs) {
			fabLink(el);
		}

		return {
			controller: homeFabController,
			controllerAs: 'fab',
			link: homeFabLink,
			templateUrl: 'app/components/home/home-fab/home-fab.tpl.html'
		}

	}


})();