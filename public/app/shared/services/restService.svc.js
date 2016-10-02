(function() {


	'use strict';

	angular
		.module('iserveu')
		.service('REST', ["$filter", "$stateParams", restService]);

	function restService($filter, $stateParams) {

		this.post = {
			makeData: function (type, data) {
				var fd = { id: $stateParams.id };
				// Object.keys(data).length just isn't working here so it's not very reusable...
				if ( type === 'address' || type === 'last_name' ) 
					fd = this.makeMutlipleData(fd, data);
				else
					fd[type] = data;
				return fd;
			},
		 	makeMutlipleData: function (fd, data) {
				for( var i in data )
					if( data[i] )
						fd[i] = data[i];
				return fd;
			}
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

		/**
		*	Private method. Transforms object array into FormData type to post to API.
		*/
		function transformToFormData(data) {
			var _fd = new FormData();
            angular.forEach(data, function (val, key) {
            	if(!val) return;

            	if(typeof val === 'object' && Object.keys(val).length !== 0)
                    transformObjectToFormData(_fd, val, key);
                else if(key.charAt(0) !== '$' && typeof val === 'string' || typeof val === 'number')
                	_fd.append(key, val);
            });

            function transformObjectToFormData(fd, obj, key) {
            	angular.forEach(obj, function(i, e){
            		if(typeof i === 'object'){
            			var t = key+'['+e+']';
                		if(i instanceof File)
                			fd.append(t, i)
                		// checks for primitive number and string that does not begin with $
                		if (typeof e === 'number' || ( e.charAt(0) !== '$' ))
                			transformObjectToFormData(fd, i, t);
                	} else
                		fd.append(key+'['+e+']', i);
            	});
            }
            return _fd;
		}

		/**
		*	Function to post to API given method and endpoint.
		*/
		this.callMethodToApi = function(data, target, method) {
				var deferred = $q.defer();
				var fd = transformToFormData(data);

				// laravel hack as the patch method is really a post method.
				if(method === 'PATCH'){ 
					fd.append('_method', 'PATCH');
					method = 'POST';
				}
 
				$http({
					method: method,
					url: target,
					data: fd || {},
					transformRequest: angular.identity,
					headers: {
						'Content-Type': undefined
					}
				}).success(function(r) {
					deferred.resolve(r);
				}).error(function(e) {
					console.log(e);
					deferred.reject(e);
				});

				return deferred.promise;


		}



	}

})();