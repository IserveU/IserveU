(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('comment', comment);

	function comment($resource) {

		var Comment = $resource('api/comment/:id', {}, {
	        'update': { method:'PUT' }
	    });

		function getComment() {
			return Comment.query().$promise.then(function(results) {
				return results
			}, function(error) {
				console.log(error);
			});
		}

		function saveComment(data) {
			return Comment.save(data).$promise.then(function(success) {
				return success;
			}, function(error) {
				return error;
			});
		}

		function updateComment(data) {
			return Comment.update({id:data.id}, data).$promise.then(function(success) {
				console.log(success);
				return success;
			}, function(error) {
				return error;
			});
		}

		function deleteComment(id) {
			return Comment.delete({id:id}).$promise.then(function(success) {
				
			}, function(error) {
				
			});
		}

		return {
			saveComment: saveComment,
			updateComment: updateComment,
			deleteComment: deleteComment,
			getComment: getComment
		}
	}
})();