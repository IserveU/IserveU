(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('utils', ['$filter', utils]);

	function utils($filter) {

		function capitalize(string) {
			return string.charAt(0).toUpperCase() + string.slice(1);
		}

		var date = {
			stringify: function(date) {
				if( date instanceof Date )
					return $filter('date')(date, "yyyy-MM-dd HH:mm:ss");
				return this.parse(date);
			},
			parse: function(date) {
				return $filter('date')( (new Date(date)), "yyyy-MM-dd HH:mm:ss");
			}
		}

		// @ http://stackoverflow.com/questions/22898927/injecting-scope-into-an-angular-service-function
		function clearArray(array) {
			return array.splice(0, array.length);
		}

		function isElementInViewport(el) {
		    if ( nullOrUndefined(el) ){
		    	return false;
		    }

		    var rect = el.getBoundingClientRect();

		    return (
		        rect.top >= 0 &&
		        rect.left >= 0 &&
		        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) && /*or $(window).height() */
		        rect.right <= (window.innerWidth || document.documentElement.clientWidth) /*or $(window).width() */
		    );
		}

		function nullOrUndefined (val) {
			return val === null || angular.isUndefined(val);
		}

		function objectIsEmpty(obj) {
			return Object.keys(obj).length === 0;
		}

		function parseStringToObject() {
			return function(json_string, obj, path) {
				var pars_str = JSON.parse(json_string);
				return obj = path ? pars_str[path] : pars_str;
			};
		}

		function toTitleCase(str) {
		    return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
		}
		
		function transformObjectToArray(obj) {
			var tmp = [];
			angular.forEach(obj, function(el, key) {
				tmp.push(el);
			});
			return tmp;
		}

		return { 
			capitalize: capitalize,
			date: date,
			clearArray: clearArray,
			isElementInViewport: isElementInViewport,
			parseStringToObject: parseStringToObject,
			nullOrUndefined: nullOrUndefined,
			objectIsEmpty: objectIsEmpty,
			toTitleCase: toTitleCase,
			transformObjectToArray: transformObjectToArray
		}
	}

})();