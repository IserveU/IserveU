(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('commentResource', ['$resource', '$q', commentResource]);

	function commentResource($resource, $q) {

		/****************************************************************
		*
		*	Resource setters using Angular's internal ngResource.
		*
		*****************************************************************/

		var Comment = $resource('api/comment/:id', {}, {
	        'update': { 
	        	method:'PUT',
		        ignoreLoadingBar: true 
	        },
        	query: {
			    method: 'GET',
		        params: {},
		        isArray: true,
		        ignoreLoadingBar: true
	    	}
	    });

	    var Restore = $resource('api/comment/:id/restore');

	    var endpoint = '/api/comment/';

	    /*****************************************************************
	    *
	    *	Server-side functions.
	    *
	    ******************************************************************/

		function getComment(id) {
			return Comment.get({id:id}).$promise.then(function(results) {
				return results;
			}, function(error) {
				return $q.reject(error);
			});
		}

		function getComments() {
			return Comment.query().$promise.then(function(results) {
				return results;
			}, function(error) {
				return $q.reject(error);
			});
		}

		function saveComment(data) {
			return Comment.save(data).$promise.then(function(success) {
				return success;
			}, function(error) {
				return $q.reject(error);
			});
		}

		function updateComment(data) {
			return Comment.update({id:data.id}, data).$promise.then(function(success) {
				return success;
			}, function(error) {
				return $q.reject(error);
			});
		}

		function deleteComment(id) {
			return Comment.delete({id:id}).$promise.then(function(success) {
				return success;
			}, function(error) {
				return $q.reject(error);
			});
		}

		function restoreComment(id){
			return Restore.get({id:id}).$promise.then(function(success) {
				return success;
			}, function(error){
				return $q.reject(error);
			})
		}

		return {
			getComment: getComment,
			getComments: getComments,
			deleteComment: deleteComment,
			restoreComment: restoreComment,
			saveComment: saveComment,
			updateComment: updateComment
		}
	}
})();