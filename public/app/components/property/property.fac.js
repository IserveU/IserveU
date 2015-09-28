(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('property', property);

	function property($resource) {

	var PropertyAssessment = $resource('api/propertyassessment');
	var PropertyBlock = $resource('api/propertyblock')
	var Property = $resource('api/property/uploadcsv', {}, {
	        'update': { method:'PUT' }
	    });

	function uploadProperties(){
		return Property.save().$promise.then(function(results){
			return results;
		}, function(error) {
			return error;
		});
	}

	function getPropertyAssessment(data){
		return PropertyAssessment.get(data).$promise.then(function(results){
			return results;
		}, function(error) {
			return error;
		});
	}

	function getPropertyBlock(data){
		return PropertyBlock.get(data).$promise.then(function(results){
			return results;
		}, function(error) {
			return error;
		});
	}

	    return {
	    	getPropertyAssessment: getPropertyAssessment,
	    	getPropertyBlock: getPropertyBlock,
	    	uploadProperties: uploadProperties
	    }

	}
})();