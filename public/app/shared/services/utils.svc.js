(function() {

	'use strict';

	angular
		.module('iserveu')
		.service('utils', utils);

	function utils() {

		this.capitalize = function(string) {
			return string.charAt(0).toUpperCase() + string.slice(1);
		}


		this.isElementInViewport = function(el) {

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


		this.objectIsGreaterThan = function(obj, max) {

			var count = 0;

			for (var i in obj)
				if(obj[i])
					count++;

			return count > max;
		}



	}


})();