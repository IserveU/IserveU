(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('ethnic_origin', ethnic_origin);

	function ethnic_origin($resource, $q) {

		var EthnicOrigin = $resource('api/ethnic_origin/:id', {}, {
	        'update': { method:'PUT' }
	    });


	   	function getEthnicOrigins(){
	   		return EthnicOrigin.query().$promise.then(function(results){
	   			return results;
	   		}, function(error){
	   			return $q.reject(error);
	   		});
	   	}

	   	function getEthnicOrigin(id){
	   		return EthnicOrigin.get({id:id}).$promise.then(function(results){
	   			return results;
	   		}, function(error){
	   			return $q.reject(error);
	   		});
	   	}

	   	return {
	   		getEthnicOrigins: getEthnicOrigins
	   	}

	}

}());