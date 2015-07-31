(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('property', property);

	function property($resource) {

	var PropertyAssessment = $resource('api/propertyassessment');

	console.log('twice');

	function getPropertyAssessment(data){
		return PropertyAssessment.get(data).$promise.then(function(results){
			return results;
		}, function(error) {
			return error;
		});
	}

	    return {
	    	getPropertyAssessment: getPropertyAssessment
	    }

	}
})();