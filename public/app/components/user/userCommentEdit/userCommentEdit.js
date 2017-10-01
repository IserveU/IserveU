(function() {
	
	angular
		.module('app.user')
		.directive('userCommentEdit', [
			'Comment',
		userCommentEdit]);

	function userCommentEdit(Comment) {

		function userCommentEditController() {
			
			var self = this; // global context for this

			self.editing = false;
			self.editComment = editComment;

			function editComment() {
				self.editing = !self.editing;
			}

		}

		return {
			controller: userCommentEditController,
			controllerAs: 'userCommentEdit',
			templateUrl: 'app/components/user/userCommentEdit/userCommentEdit.tpl.html'
		}


	}

})();