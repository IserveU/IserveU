(function() {

	'use strict';

	angular
		.module('iserveu')
		.service('utils', utils);

	function utils() {

		this.capitalize = function(string) {
			return string.charAt(0).toUpperCase() + string.slice(1);
		}

		this.toTitleCase = function(str) {
		    return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
		}

		this.isElementInViewport = function(el) {

		    if ( nullOrUndefined(el) )
		    	return 0;

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

		// Service recommendation 
		// @ http://stackoverflow.com/questions/22898927/injecting-scope-into-an-angular-service-function
		this.clearArray = function(array) {
			console.log(array);
			return array.splice(0, array.length);
		}

		this.nullOrUndefined = nullOrUndefined;
		function nullOrUndefined (val) {
			return !val || angular.isUndefined(val)
		}
		
	}


})();