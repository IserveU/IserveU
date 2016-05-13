(function() {

'use strict';

angular
	.module('iserveu')
	.factory('motionDepartments', 
		['$http',
		 'motionDepartmentResource', 
		 'errorHandler',
	
	function($http, motionDepartmentResource, errorHandler) {

	var motionDepartment = {
		index: [],
		filter: '',
		loadAll: function() {
			var self = this;
			
			if(self.index.length > 0) {
				return false;
			}

			// TODO: make a get sanitizer that strips promise and such with method below
			motionDepartmentResource.getDepartments().then(function(success){
				self.index = JSON.parse(angular.toJson(success));
			}, function(error){
				errorHandler(error);
			})
		}
	}

	return motionDepartment;

}])


})();