(function() {

	'use strict';

	angular
		.module('iserveu')
		.service('utils', ['$filter', utils]);

	function utils($filter) {

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
			return array.splice(0, array.length);
		}

		this.nullOrUndefined = nullOrUndefined;
		function nullOrUndefined (val) {
			return !val || angular.isUndefined(val)
		}
	
		this.date = {
			stringify: function(date) {
				if( date instanceof Date )
					return $filter('date')(date, "yyyy-MM-dd HH:mm:ss");
				return this.parse(date);
			},
			parse: function(date) {
				return $filter('date')( (new Date(date)), "yyyy-MM-dd HH:mm:ss");
			}
		}

		this.parseStringToObject = function() {
			return function(json_string, obj, path) {
				var parsed_string = JSON.parse(json_string);
				return obj = path ? parsed_string[path] : parsed_string;
			};
		}

		this.objectIsEmpty = function(obj) {
			return Object.keys(obj).length === 0;
		}

		
	}


})();