(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('ethnicOriginService', ['$http', ethnicOriginService]);

  	 /** @ngInject */
	function ethnicOriginService($http) {

		function getEthnicOrigins(){
	        return $http.get('api/ethnic_origin/').then(function(r){
	            return r.data;
	        });
		}

		function getEthnicOrigin(id){
	    	return $http.get('api/ethnic_origin/'+id).then(function(r){
	            return r.data;
	        });
		}

		return {
			getEthnicOrigins: getEthnicOrigins,
			getEthnicOrigin: getEthnicOrigin
		};

	}

}());