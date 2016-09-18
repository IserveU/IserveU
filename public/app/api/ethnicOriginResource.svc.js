(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('ethnicOriginResource', ['$http', ethnicOriginResource]);

  	 /** @ngInject */
	function ethnicOriginResource($http) {

		function getEthnicOrigins(){
	        return $http.get('api/ethnic_origin/').success(function(results){
	            return results;
	        }).error(function(error){
	        	return error;
	        });
		}

		function getEthnicOrigin(id){
	    	return $http.get('api/ethnic_origin/'+id).success(function(results){
	            return results;
	        }).error(function(error){
	        	return error;
	        });
		}

		return {
			getEthnicOrigins: getEthnicOrigins,
			getEthnicOrigin: getEthnicOrigin
		};

	}

}());