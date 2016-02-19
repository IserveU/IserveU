(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('homeFab', homeFab);

	function homeFab($window, $stateParams, pageObj, ToastMessage) {

		function homeFabController() {

			this.isOpen = false;

			this.edit = function () {

			};
		}

		function homeFabLink(scope, el, attrs) {

			var container = document.getElementById('maincontent');

			angular
				.element(container)
				.bind('scroll', function() {

					if( !isElementInViewport(document.getElementById('userbar')) 
						&& container.clientHeight == (container.scrollTop + 130)) {
						el.children().eq(0).css({ 'top': '10px' });
					}
					else
						el.children().eq(0).css({ 'top': '81px' });

					if( container.scrollTop < 30)
						el.children().eq(0).css({ 'top': '81px' });
			});

			angular
				.element($window)
				.bind('scroll', function() {

					if( !isElementInViewport(document.getElementById('userbar')) 
						|| $window.clientHeight == ($window.scrollTop + 130)) {
						el.children().eq(0).css({ 'top': '10px' });
					}
					else
						el.children().eq(0).css({ 'top': '81px' });

					if( $window.scrollTop < 30)
						el.children().eq(0).css({ 'top': '81px' });

			});
		}


		function isElementInViewport (el) {

		    //special bonus for those using jQuery
		    if (typeof jQuery === "function" && el instanceof jQuery) {
		        el = el[0];
		    }

		    var rect = el.getBoundingClientRect();

		    return (
		        rect.top >= 0 &&
		        rect.left >= 0 &&
		        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) && /*or $(window).height() */
		        rect.right <= (window.innerWidth || document.documentElement.clientWidth) /*or $(window).width() */
		    );
		}


		return {
			controller: homeFabController,
			controllerAs: 'fab',
			link: homeFabLink,
			templateUrl: 'app/components/home/home-fab/home-fab.tpl.html'
		}

	}


})();