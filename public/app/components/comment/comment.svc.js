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

		return {
			saveComment: saveComment
		}
	}
})();