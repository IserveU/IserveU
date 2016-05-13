(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('commentVoteResource', ['$resource', '$q', commentVoteResource]);

	function commentVoteResource($resource, $q) {

		/****************************************************************
		*
		*	Resource setters using Angular's internal ngResource.
		*
		*****************************************************************/

		var CommentVote = $resource('api/comment_vote/:id', {}, {
	        'update': { method:'PUT' }
	    });

	    /*****************************************************************
	    *
	    *	Server-side functions.
	    *
	    ******************************************************************/
	    
		function saveCommentVotes(data) {
			return CommentVote.save(data).$promise.then(function(success) {
				return success;
			}, function(error) {
				return $q.reject(error);
			});
		}

		function updateCommentVotes(data) {
			return CommentVote.update({id:data.id}, data).$promise.then(function(success) {
				return success;
			}, function(error) {
				return $q.reject(error);
			});
		}

		function deleteCommentVote(id) {
			return CommentVote.delete(id).$promise.then(function(success) {
				return success;
			}, function(error) {
				return $q.reject(error);
			});
		}

		return {
			saveCommentVotes: saveCommentVotes,
			updateCommentVotes: updateCommentVotes,
			deleteCommentVote: deleteCommentVote
		}
	}

}());