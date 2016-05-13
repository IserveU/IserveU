(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('motionDepartmentResource', ['$resource','$q', motionDepartmentResource]);

	function motionDepartmentResource($resource, $q) {

		/****************************************************************
		*
		*	Resource setters using Angular's internal ngResource.
		*
		*****************************************************************/

		var Department = $resource('api/department/:id', {}, {
	        'update': { method:'PUT' }
	    });

	    /*****************************************************************
	    *
	    *	Server-side functions.
	    *
	    ******************************************************************/

		function getDepartments() {
			return Department.query().$promise.then(function(success) {
				return success;
			}, function(error) {
				return $q.reject(error);
			});
		}


		function addDepartment(data){
			return Department.save(data).$promise.then(function(success) {
				return success;
			}, function(error) {
				return $q.reject(error);
			});
		}

		function deleteDepartment(id){
			return Department.delete({id:id}).$promise.then(function(success) {
				return success;
			}, function(error) {
				return $q.reject(error);
			});
		}

		function updateDepartment(data){
			return Department.update({id:data.id}, data).$promise.then(function(success) {
				return success;
			}, function(error) {
				return $q.reject(error);
			});
		}


		return {
				getDepartments: getDepartments,
				addDepartment: addDepartment,
				deleteDepartment: deleteDepartment,
				updateDepartment: updateDepartment,
		}


	}

})();