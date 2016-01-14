(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('role', role);

	function role($resource, $q) {

		var Role = $resource('api/role');

		var UserRole = $resource('api/user/:id/role/:role_id', {id:'@user_id', role_id:'@role_id'}, {
	        'update': { method:'PUT' }
	    });

		function getRoles(){
			return Role.query().$promise.then(function(results){
				return results;
			}, function(error) {
				return $q.reject(error);
			})
		}

		function grantRole(data){
			return UserRole.save({id:data.user_id}, data).$promise.then(function(results){
				return results;
			}, function(error) {
				return $q.reject(error);
			})
		}

		function getUserRole(id){
			return UserRole.query({id:id}).$promise.then(function(results){
				return results;
			}, function(error) {
				return $q.reject(error);
			})
		}

		function deleteUserRole(data){
			return UserRole.delete({id:data.user_id, role_id:data.role_id}).$promise.then(function(results){
				return results;
			}, function(error) {
				return $q.reject(error);
			})
		}

		return {
			getRoles: getRoles,
			grantRole: grantRole,
			getUserRole: getUserRole,
			deleteUserRole: deleteUserRole
		}

	}


}());