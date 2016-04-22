/**
 * @description
 * @name isuApiProvider
 */

angular.module('iserveu')
.provider('$isuApiProvider', function() {
	'use strict';

	this.defaults = {
		serverFramework: 'laravel',
		useDefaultTemplate: true,
		method: 'POST',
		target: '/',
		fileEndpoint: '/file', 
		templateDefaults: null,
		types: null,
		possibleTypes: [{
            type: 'Text',
            html: '<text-section></text-section>',
            url: '/section/tpl/textSection.tpl.html', // these URLs aren't working yet
        },
        {
            type: 'InlineImage',
            html: '<inline-image-section></inline-image-section>',
            url: '/section/tpl/inlinePhotoSection.tpl.html',
            schema: { image: { file: null, description: null, user_id: null }, 
            		  file_id: null, url: null }
	    },
        {
            type: 'Profile',
            html: '<profile-section></profile-section>',
            url: '/section/tpl/ProfileSection.tpl.html',
	    }]
	};
	
	this.$get = ['$http', '$q', function($http, $q) {
		return {

			/** Default configuration */
			defaults: this.defaults,

			/**
			*	Grabs the possible types in defaults.
			*/
			possibleTypes: function() {
				return this.defaults.types || this.defaults.possibleTypes.map(function(o) { return o.type; });
			},

			/**
			*	Returns the schema of the section type for front end.
			*	Primarily important for creating content arrays.
			*/
			getContentOf: function(type) {
				return this.defaults.possibleTypes.map(function(o){
					return o.type === type ? ( o.schema || undefined ) : undefined;
				}).filter(function(o){
					return !angular.isUndefined(o);
				});
			},

			/**
			*	
			*
			*/
			transformDate: {
				stringify: function(date) {
					if( angular.isString(date) )
						return this.parse(date);
					return $filter('date')(date, "yyyy-MM-dd HH:mm:ss");
				},
				parse: function(date) {
					return $filter('date')( (new Date(date)), "yyyy-MM-dd HH:mm:ss");
				}
			},

			/** @Should be a private method and check for date type fields.
			*	Transforms object array into FormData type to post to API.
			*/
			transformToFormData: function(data) {  //consider exporting this into a service library for personal usage;
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
			},

			/**
			*	Function to post to API given method and endpoint.
			*   @Rename to callMethodToApi
			*/  
			callMethodToApi: function(data) {
				var deferred = $q.defer();
				var fd = this.transformToFormData(data);

				// laravel hack as the patch method is really a post method.
				if(this.defaults.serverFramework === "laravel" && this.defaults.method === 'PATCH'){ 
					fd.append('_method', 'PATCH');
					this.defaults.method = 'POST';
				}
 
				$http({
					method: this.defaults.method,
					url: this.defaults.target,
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
			},

			/**
			*	Functional data transformer that takes file_id, grabs filename,
			*	and mutates date timestamps into angular form acceptable types.
			*/
			apiTransformer: function(data) {

			}
		}
	}];

});

