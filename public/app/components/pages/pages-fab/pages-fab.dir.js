(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('pagesFab', pagesFab);

	function pagesFab($window, $stateParams, pageObj, ToastMessage) {

		function pagesFabController() {

			this.pageObj = pageObj;

			this.isOpen = false;

			this.destroy = function () {

				ToastMessage.destroyThis("page", function() {
					pageObj.delete($stateParams.id);
				});
			};
		}

		function pagesFabLink(scope, el, attrs) {

			var container = document.getElementById('maincontent');

			angular
				.element(container)
				.bind('scroll', function() {

					console.log(content.clientHeight);
					console.log(content.scrollTop);


					if( !isElementInViewport(document.getElementById('userbar')) 
						|| container.clientHeight == (container.scrollTop + 130)) {
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
			controller: pagesFabController,
			controllerAs: 'fab',
			link: pagesFabLink,
			templateUrl: 'app/components/pages/pages-fab/pages-fab.tpl.html'
		}

	}


})();