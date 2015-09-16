(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('comment', comment);

	function comment($resource, $q) {

		var Comment = $resource('api/comment/:id', {}, {
	        'update': { method:'PUT' }
	    });

	    var Restore = $resource('api/comment/:id/restore');

	    var MyComments = $resource('api/user/:id/comment');

	    var MotionComment = $resource('api/motion/:id/comment');



		function getComment() {
			return Comment.query().$promise.then(function(results) {
				return results
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

		function getMyComments(id) {
			return MyComments.query({id:id}).$promise.then(function(results) {
				return results;
			}, function(error) {
				return $q.reject(error);
			});
		}

		function getMotionComments(id) {
			return MotionComment.get({id:id}).$promise.then(function(result) {
				return result;
			}, function(error) {
				return $q.reject(error);
			});
		}

		return {
			saveComment: saveComment,
			updateComment: updateComment,
			deleteComment: deleteComment,
			restoreComment: restoreComment,
			getComment: getComment,
			getMyComments: getMyComments,
			getMotionComments: getMotionComments
		}
	}
})();