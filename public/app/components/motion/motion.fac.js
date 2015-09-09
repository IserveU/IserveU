(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('motion', motion);

	function motion($resource, $q) {

		var Motion = $resource('api/motion/:id', {}, {
	        'update': { method:'PUT' }
	    });

	    var GetTopMotion = $resource('api/motion/', {
               rank_greater_than:0, take:1
		}, {});

	    var MotionRestore = $resource('api/motion/:id/restore');

		function getMotions(data) {
			return Motion.get(data).$promise.then(function(results) {
				return results
			}, function(error) {
				return $q.reject(error);
			});
		}

		function getMotion(id) {
			return Motion.get({id:id}).$promise.then(function(result) {
				return result;
			}, function(error) {
				return $q.reject(error);
			});
		}

		function updateMotion(data) {
			return Motion.update({id:data.id}, data).$promise.then(function(result) {
				return result;
			}, function(error) {
				return $q.reject(error);
			});
		}

		function createMotion(data) {
			return Motion.save(data).$promise.then(function(success) {
				return success;
			}, function(error) {
				return $q.reject(error);
			});
		}

		function deleteMotion(id) {
			return Motion.delete({id:id}).$promise.then(function(success) {
				return success;
			}, function(error) {
				return $q.reject(error);
			});

		}

		function restoreMotion(id) {
			return MotionRestore.get({id:id}).$promise.then(function(success) {
				return success;
			}, function(error) {
				return $q.reject(error);
			});
		}

		function getTopMotion() {
			return GetTopMotion.get().$promise.then(function(results) {
				return results
			}, function(error) {
				return $q.reject(error);
			});
		}

		return {
			getMotions: getMotions,
			getMotion: getMotion,
			createMotion: createMotion,
			updateMotion: updateMotion,
			deleteMotion: deleteMotion,
			restoreMotion: restoreMotion,
			getTopMotion: getTopMotion,
		}
	}
})();