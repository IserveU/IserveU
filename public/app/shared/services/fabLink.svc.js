(function() {

	'use strict';

	angular
		.module('iserveu')
		.service('fabLink', fabLink);

	function fabLink($window, utils) {

		return function(el) {

			var container = document.getElementById('maincontent');

			angular
				.element(container)
				.bind('scroll', function() {

					if( !utils.isElementInViewport(document.getElementById('userbar')) 
						&& container.clientHeight == (container.scrollTop + 130)) {
						el.children().eq(0).children().eq(0).css({ 'top': '10px' });
					}
					else
						el.children().eq(0).children().eq(0).css({ 'top': '81px' });

					if( container.scrollTop < 10)
						el.children().eq(0).children().eq(0).css({ 'top': '81px' });
			});

			angular
				.element($window)
				.bind('scroll', function() {

					if( !utils.isElementInViewport(document.getElementById('userbar')) 
						|| $window.clientHeight == ($window.scrollTop + 30))
						el.children().eq(0).children().eq(0).css({ 'top': '10px' });
					else
						el.children().eq(0).children().eq(0).css({ 'top': '81px' });

					if( $window.scrollTop < 30)
						el.children().eq(0).children().eq(0).css({ 'top': '81px' });

			});
		}







	}



})();