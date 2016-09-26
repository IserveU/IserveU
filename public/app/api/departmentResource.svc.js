(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.factory('departmentResource', ['$http', '$q', 'utils', departmentResource]);

	function departmentResource($http, $q, utils) {

		var index = {};

		function getDepartments () {

			if( !utils.objectIsEmpty(index) ) {
				return $q.when({data: index});
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

