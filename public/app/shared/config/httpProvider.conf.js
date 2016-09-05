(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.config(['$httpProvider',

	function($httpProvider){

		$httpProvider.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest"; // for AJAX
		// $httpProvider.defaults.headers.common["X-CSRF-TOKEN"] = localStorage.getItem('api_token');

		$httpProvider.interceptors.push(['$q', '$injector', function($q, $injector) {
		    return {
		      'request': function(config) {


		      	// LARAVEL HACK
		      	if(config.method === "PATCH" || config.method === "PUT") {
			      	config.transformRequest = transformToFormData;
		      		config.headers[ "Content-Type" ] = undefined;
		      		config.headers["X-HTTP-Method-Override"] = "PATCH";
		      		config.method = "POST";
		      	}

		      	if(config.method !== "GET") {
		      		config.params = config.params || {};
		      		config.params.api_token = localStorage.getItem('api_token');
		      	}

		        return config || $q.when(config);
		      },
		      'response': function(config) {
		        return config || $q.when(config);
		      },
		      'responseError': function(config) {
		      	return $q.reject(config);
		      }
		    };

		}]);


		/**
		*	Transforms object array into FormData type to post to API.
		*/
		function transformToFormData(data, getHeaders) {
			var _fd = new FormData();

            angular.forEach(data, function (val, key) {
            	if(val === null || 
            	   val === '' || 
            	   angular.isUndefined(val) ||
            	   key.charAt(0) === '$') return;

            	if(typeof val === 'object' && Object.keys(val).length !== 0)
                    transformObjectToFormData(_fd, val, key);
                else if(key.charAt(0) !== '$' && typeof val === 'string' || typeof val === 'number' || val instanceof File)
            		_fd.append(key, val);
            });

            function transformObjectToFormData(fd, obj, key) {
            	angular.forEach(obj, function(i, e){
            		if(typeof i === 'object'){

            			if(typeof e === 'string' && e.charAt(0) === '$') return;

            			var t = key+'['+e+']';
                		if(i instanceof File){
                			fd.append(t, i)
                		}
                		// checks for primitive number and string that does not begin with $
                		else if (Array.isArray(e) || typeof e === 'object' || typeof e === 'number' || ( typeof e === 'string' && e.charAt(0) !== '$'))
                			transformObjectToFormData(fd, i, t);


                	} else if(!angular.isUndefined(i))
                		fd.append(key+'['+e+']', i);
            	});
            }
            return _fd;
		}

	}]);


})();