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
	    	},
	    });

	    var Restore = $resource('api/comment/:id/restore');

	    var CreateComment = $resource('api/vote/:vote_id/comment', {ignoreLoadingBar:'@true'});

	    var UserComment = $resource('api/user/:user_id/comment', {}, {
	    	query: {
	    		method: 'GET',
	    		params: {},
	    		ignoreLoadingBar: true
	    	}
	    })

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

		function getUserComments(data) {
			return UserComment.query({user_id: data.user_id}).$promise.then(function(results) {
				return results;
			}, function(error) {
				return $q.reject(error);
			});
		}

		function saveComment(data) {
			return CreateComment.save({vote_id: data.vote_id}, {text: data.text}).$promise.then(function(success) {
				return success;
			}, function(error) {
				return $q.reject(error);
			});
		}

		function updateComment(data) {
			return Comment.update({id:data.id}, {text: data.text}).$promise.then(function(success) {
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
			getUserComments: getUserComments,
			deleteComment: deleteComment,
			restoreComment: restoreComment,
			saveComment: saveComment,
			updateComment: updateComment
		}
	}
})();