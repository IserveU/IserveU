(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('ethnicOriginService', ethnicOriginService);

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

		var factObj = {
		    ethnicOrigins: null,
		    getEthnicOrigins: function(){
		        return $http.get('api/ethnic_origin/').then(function(r){
		            factObj.ethnicOrigins = r.data;
		        });
		    },
		    getEthnicOrigin: function(id){
		    	$http.get('api/ethnic_origin/'+id).then(function(r){
		    		console.log(r.data.region);
		            return r.data;
		        });
		    }
		}

		// factObj.getEthnicOrigins();

		return {
			getEthnicOrigins: getEthnicOrigins,
			getEthnicOrigin: getEthnicOrigin
		};

	}

}());