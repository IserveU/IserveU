(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('comment', comment);

	function comment($resource) {

		var Comment = $resource('api/comment/:id');

		function saveComment(data) {
			return Comment.save(data).then(function(success) {
				return sucess;
			}, function(error) {
				return error;
			});
		}

		return {
			saveComment: saveComment
		}
	}
})();