(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('motionResource', ['$rootScope', '$resource', '$q', '$http', motionResource]);

	function motionResource($rootScope, $resource, $q, $http) {

		var customQuery = {
			query: {
			    method: 'GET',
		        params: {},
		        isArray: true,
		        ignoreLoadingBar: true,
		        transformRequest: angular.identity
	    	}
		};


		/****************************************************************
		*
		*	Resource setters using Angular's internal ngResource.
		*
		*****************************************************************/

		var Motion = $resource('api/motion/:id', {}, {
	        'update': { method:'PUT' }
	    });

	    var MotionComments = $resource('api/motion/:id/comment', {}, customQuery);

		var MotionFiles = $resource('api/motion/:motion_id/motionfile/', {}, customQuery);

		var MotionIndex = $resource('api/motion', {page: '@pnext_page'}, {
			query: {
				method: 'GET',
				ignoreLoadingBar: true,
				cancellable: true
			}
		});

	    var MotionRestore = $resource('api/motion/:id/restore');

	    var MotionVotes = $resource('api/motion/:id/vote');

	    var TopMotion = $resource('api/motion/', {}, { 
			query: {
		        method: 'GET',
		        params: {
		        	rank_greater_than: 0,
		        	take: 1
		        },
		        isArray: true,
				ignoreLoadingBar: true
			}
		});

	    /*****************************************************************
	    *
	    *	Server-side functions.
	    *
	    ******************************************************************/

		function getMotion(id) {
			return Motion.get({id:id}).$promise.then(function(success) {
				return success;
			}, function(error) {
				return $q.reject(error);
			});
		}

		function getMotionByStatus(status) {
			return $http({
                method: "GET",
                url: "/api/motion",
                params: {
                     'status[]': status
                },
                ignoreLoadingBar: true
          	}).then(function(success){
				return success;
			}, function(error){
				return error;
			});
		}

		function getMotionComments(id) {
			return MotionComments.get({id:id}).$promise.then(function(success){
				return success;
			}, function(error) {
				return $q.reject(error);
			});
		}

		function getMotionFiles(id) {
			return MotionFiles.query({motion_id:id}).$promise.then(function(success){
				return success;
			}, function(error) {
				return $q.reject(error);
			});
		}

		function getMotionVotes(id) {
	    	return $http.get('api/motion/'+id+'/vote', {
		        withCredentials: true,
		        headers: {'Content-Type': undefined },
		        ignoreLoadingBar: true,
		        transformRequest: angular.identity
		    }).success(function(success) {
				return success;
			}).error(function(error) {
				return error;
			});

		}

		function getMyMotions() {
			return $http({
                method: "GET",
                url: "/api/motion",
                params: {
                     'status[]': [0],
                     user_id: $rootScope.authenticatedUser.id
                },
                ignoreLoadingBar: true
          	}).then(function(success){
				return success;
			}, function(error){
				return error;
			});
		}
		
		function getMotions(data) {
			return Motion.get(data).$promise.then(function(succcess) {
				return succcess;
			}, function(error) {
				return $q.reject(error);
			});
		}

		function getMotionsIndex(next_page) {
			return MotionIndex.query({page: next_page}).$promise.then(function(success) {
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

		function updateMotion(data) {
			return Motion.update({id:data.id}, data).$promise.then(function(success) {
				return success;
			}, function(error) {
				return $q.reject(error);
			});
		}
	

		return {
			// GET
			getMotion: getMotion,
			getMotionByStatus: getMotionByStatus,
			getMotionFiles: getMotionFiles,
			getMotionComments: getMotionComments,
			getMotionVotes: getMotionVotes,
			getMotions: getMotions,
			getMotionsIndex: getMotionsIndex,
			getMyMotions: getMyMotions,
			restoreMotion: restoreMotion,

			// POST
			createMotion: createMotion,
			deleteMotion: deleteMotion,
			updateMotion: updateMotion,
		}

	}
})();