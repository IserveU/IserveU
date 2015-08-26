(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('department', department);

	function department($resource) {

		var Department = $resource('api/department/:id', {}, {
	        'update': { method:'PUT' }
	    });
		function getDepartments(){
			return Department.query().$promise.then(function(results) {
				return results;
			}, function(error) {
				console.log(error);
			});
		}

		function addDepartment(data){
			return Department.save(data).$promise.then(function(success) {
				return success;
			}, function(error) {
				return error;
			});
		}

		function deleteDepartment(id){
			return Department.delete({id:id}).$promise.then(function(success) {
				return success;
			}, function(error) {
				return error;
			});
		}

		function updateDepartment(data){
			return Department.update({id:data.id}, data).$promise.then(function(success) {
				return success;
			}, function(error) {
				return error;
			});
		}

	return {
			getDepartments: getDepartments,
			addDepartment: addDepartment,
			deleteDepartment: deleteDepartment,
			updateDepartment: updateDepartment
		}




	}

})();