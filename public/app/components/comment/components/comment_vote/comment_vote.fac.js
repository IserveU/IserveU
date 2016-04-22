(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('commentvote', ['$resource', '$q', commentvote]);

	/** @ngInject */
	function commentvote($resource, $q) {

		var CommentVote = $resource('api/comment_vote/:id', {}, {
	        'update': { method:'PUT' }
	    });

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
			return CommentVote.delete({id:id}).$promise.then(function(success) {
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