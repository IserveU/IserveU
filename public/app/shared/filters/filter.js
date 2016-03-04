(function() {

	angular
		.module('iserveu')

		.filter('capitalize', function() {
		    return function(input) {
		      return (!!input) ? input.charAt(0).toUpperCase() + input.substr(1).toLowerCase() : '';
		    }
		})
		.filter('dateToDate', function() {
		  	return function(input) {
		    	input = new Date(input);
		    	return input;
	  		};
		})
		.filter('proComment', function() {
			return function(input) {
				var out = [];
				for(var i = 0; i < input.length; i++) {
					if(input[i].position == "1") {
						out.push(input[i])
					}				
				}
				return out;
			}
		})
		.filter('conComment', function() {
			return function(input) {
				var out = [];
				for(var i = 0; i < input.length; i++) {
					if(input[i].position == "0" || input[i].position == "-1") {
						out.push(input[i])
					}				
				}
				return out;
			}
		})
		.filter('object2Array', function() {
		    return function(obj) {
		    	return Object.keys(obj).map(function(key){return obj[key];});
		    }
	 	})
	 	.filter('bytes', function() {
			return function(bytes, precision) {
				if (isNaN(parseFloat(bytes)) || !isFinite(bytes)) return '-';
				if (typeof precision === 'undefined') precision = 1;
				var units = ['bytes', 'kB', 'MB', 'GB', 'TB', 'PB'],
					number = Math.floor(Math.log(bytes) / Math.log(1024));
				return (bytes / Math.pow(1024, Math.floor(number))).toFixed(precision) +  ' ' + units[number];
			}
		})
		.filter('trustAsHtml', function($sce) {
			return function (value) {
				return $sce.trustAsHtml(value);
			}
		})

})();