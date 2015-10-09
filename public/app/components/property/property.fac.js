(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('property', property);

	function property($resource, ToastMessage) {

	var PropertyAssessment = $resource('api/propertyassessment');

	var PropertyBlock = $resource('api/propertyblock')

	var PropertyCSV = $resource('api/property/uploadcsv');

	var Property = $resource('api/property/:id', {search_query_street_name:'@street_name'}, {
			'update': { method: 'PUT'}
		});

	function searchProperty(data){
		return Property.query(data).$promise.then(function(results){
			return results;
		}, function(error) {
			ToastMessage.report_error(error);
		});
	}

	function updateProperty(data){
		return Property.update({id:data.id},data).$promise.then(function(results){
			return results;
		}, function(error) {
			ToastMessage.report_error(error);
		});
	}

	function uploadProperties(){
		return PropertyCSV.save().$promise.then(function(results){
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
	    	uploadProperties: uploadProperties,
	    	searchProperty: searchProperty,
	    	updateProperty: updateProperty
	    }

	}
})();