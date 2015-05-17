(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('comment', comment);

	function comment($resource) {

		var Comment = $resource('api/comment/:id');

		function saveComment(data) {
			return Comment.save(data).$promise.then(function(success) {
				return success;
			}, function(error) {
				return error;
			});
		}

		function deleteComment(id) {
			return Comment.delete({id:id}).$promise.then(function(success) {
				console.log('Service says: Success message from the service, although the delete might night have gone through.');
			}, function(error) {
				console.log('Service says: Comment delete error');
			});
		}

		return {
			saveComment: saveComment,
			deleteComment: deleteComment
		}
	}
})();