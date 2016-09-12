(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.factory('departmentResource', ['$http', '$q', 'utils', departmentResource]);

	function departmentResource($http, $q, utils) {

		var index = {};
		var deferred = $q.defer();

		function getDepartments () {

			if( !utils.objectIsEmpty(index) ) {
				deferred.resolve({data: index}); 
				return deferred.promise;
			}

			return $http({
				method: 'GET',
				url: '/api/department',
				ignoreLoadingBar: true,
			}).success(function(results){
				index = results;
				return results;
			}).error(function(error){
				return error;
			});
		}

		return {
			getDepartments: getDepartments,
			index: index
		}
	}

})();

