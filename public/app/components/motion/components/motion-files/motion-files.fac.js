(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('motionfile', motionfile);

	 /** @ngInject */
	function motionfile($resource, $q, $http) {

		function uploadMotionFile(motion_id, fd){
			return $http.post('api/motion/'+motion_id+'/motionfile/', fd, {
		        withCredentials: true,
		        headers: {'Content-Type': undefined },
		        transformRequest: angular.identity
		    }).success(function(result) {
				return result;
			}).error(function(error) {
				return error;
			});
		}

		// set up resources from ng-resource
		var MotionFile = $resource('api/motion/:motion_id/motionfile/:file_id', {motion_id:'@motion_id', file_id:'@file_id'}, {
	        'update': { method:'PUT' }
	    });

		function getMotionFiles(motion_id){
			return MotionFile.query({motion_id:motion_id}).$promise.then(function(results){
				return results;
			}, function(error) {
				return $q.reject(error);
			});
		}


		function getMotionFile(motion_id, file_id){
			return MotionFile.query({motion_id:motion_id, file_id: file_id}).$promise.then(function(results){
				return results;
			}, function(error) {
				return $q.reject(error);
			});
		}

		// might function wonky, if that happens add a method for PUT
		function updateMotionFile(data, motion_id, file_id){
			return MotionFile.update(data, {motion_id:motion_id, file_id:file_id}).$promise.then(function(results){
				return results;
			}, function(error) {
				return $q.reject(error);
			});
		}

		function deleteMotionFile(motion_id, file_id){
			return MotionFile.delete({motion_id:motion_id, file_id:file_id}).$promise.then(function(results){
				return results;
			}, function(error) {
				return $q.reject(error);
			});
		}

		return {
			getMotionFiles: getMotionFiles,
			uploadMotionFile: uploadMotionFile,
			getMotionFile: getMotionFile,
			updateMotionFile: updateMotionFile,
			deleteMotionFile: deleteMotionFile
		}


	}

})();