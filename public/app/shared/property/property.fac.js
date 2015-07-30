(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('property', property);

	function property($resource) {

		// var UploadProperty = $resource('api/property/uploadcsv', {}, {
	 //        'update': { method:'PUT' }
	 //    });

	 //    function uploadProperties(){
	 //    	return UploadProperty.save().$promise.then(function(results) {
		// 		return results;
		// 	}, function(error) {
		// 		return error;
		// 	});
	 //    }


	 //    return {
	 //    	uploadProperties: uploadProperties
	 //    }

	}
})();