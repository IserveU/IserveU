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

		function retrieveNameById(id) {
			if( utils.objectIsEmpty(index) ) {
				return getCommunities().then(function(results){
					return retrieveNameById(id);
				});
			} else {
				var name;
				index.forEach(function(el) {
					if (el.id == id) { name = el.name; }
				});

				return $q.when(name);
			}

		}

		return {
			getCommunities: getCommunities,
			retrieveNameById: retrieveNameById,
			index: index
		}
	}

})();

