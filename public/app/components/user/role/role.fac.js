(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('role', role);

	function role($resource, $q) {

		var Role = $resource('api/role');

		var Grant = $resource('api/role/grant');

		function getRoles(){
			return Role.query().$promise.then(function(results){
				return results;
			}, function(error) {
				return error;
			})
		}

		function grantRole(data){
			return Grant.save(data).$promise.then(function(results){
				return results;
			}, function(error) {
				return error;
			})
		}

		return {
			getRoles: getRoles,
			grantRole: grantRole
		}

	}


}());