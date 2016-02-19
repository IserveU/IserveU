(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('department', department);

	function department($resource, $http, $q, $timeout) {

		var Department = $resource('api/department/:id', {}, {
	        'update': { method:'PUT' }
	    });

		var self = {
			data: {},
			getData: function() {
				console.log(self.data.hasOwnProperty(0));

				if(self.data.hasOwnProperty(0)){
					console.log('foo');
					return self.data;
				}
				else {
					self.initDepartments();
					console.log(self.data);
					$timeout(function() {
						self.getData();
					}, 600);
				}
			},
			initDepartments: function() {
				Department.query().$promise.then(function(r) {
					console.log(r.hasOwnProperty(0));
					self.data = r;
				});
			},
			getDepartments: function() {
				return $http.get('api/department/').success(function(result) {
					console.log(result);
					return result.data;
				});
			}
		};

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

		self.initDepartments();

	return {
			self: self,
			addDepartment: addDepartment,
			deleteDepartment: deleteDepartment,
			updateDepartment: updateDepartment,
		}




	}

})();