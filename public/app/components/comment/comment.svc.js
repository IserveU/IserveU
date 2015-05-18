(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('comment', comment);

	function comment($resource) {

		var Comment = $resource('api/comment/:id', null, {
	        'update': { method:'PUT' }
	    });

		function saveComment(data) {
			return Comment.save(data).$promise.then(function(success) {
				return success;
			}, function(error) {
				return error;
			});
		}

		function updateComment(id, text) {
			return Comment.update({id:id, text:text}).$promise.then(function(success) {

			}, function(error) {

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
			deleteComment: deleteComment
		}
	}
})();