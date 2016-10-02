(function() {

	'use strict';

	angular
		.module('iserveu')
		.service('floatingButtonService', ['$window', 'utils', floatingButtonService]);

  	 /** @ngInject */
	function floatingButtonService($window, utils) {

		return function(el) {

			var container = document.getElementById('maincontent'),
				element   = el.children().eq(0).children().eq(0);

			angular
				.element(container)
				.bind('scroll', function() {

					if( !utils.isElementInViewport(document.getElementById('userbar')) 
						&& container.clientHeight == (container.scrollTop + 130)) 
						element.css({ 'top': '10px' });
					else
						element.css({ 'top': '81px' });

					if( container.scrollTop == 55)
						element.css({ 'top': '81px' });
			});

			angular
				.element($window)
				.bind('scroll', function() {

					if( !utils.isElementInViewport(document.getElementById('userbar')) 
						|| $window.clientHeight == ($window.scrollTop + 130))
						element.css({ 'top': '10px' });
					else
						element.css({ 'top': '81px' });

					if( $window.scrollTop == 55)
						element.css({ 'top': '81px' });

			});
		};



	}
})();