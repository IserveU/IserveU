(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.factory('communityResource', ['$http', '$q', 'utils', communityResource]);

	function communityResource($http, $q, utils) {

		var index = {};

		function getCommunities () {

			if( !utils.objectIsEmpty(index) ) {
				return $q.when({data: index});
			}

			return $http({
				method: 'GET',
				url: '/api/community',
				ignoreLoadingBar: true,
			}).success(function(results){
				index = results;
				return results;
			}).error(function(error){
				return error;
			});
		}

		return {
			getCommunities: getCommunities,
			index: index
		}
	}

})();

