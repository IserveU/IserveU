(function() {
	
	angular
		.module('iserveu')
		.directive('userCommentCreate', ['Comment', userCommentCreate]);

	function userCommentCreate(Comment) {

		function userCommentCreateController() {
			
			var self = this; // global context for this

			self.comment = null;
			self.writing = false;
			self.writeComment = writeComment;

			function writeComment() {
				self.writing = !self.writing;
				if(!self.comment)
					self.comment = new Comment();
			}
			
		}


		return {
			controller: userCommentCreateController,
			controllerAs: 'userCommentCreate',
			templateUrl: 'app/components/userCommentCreate/userCommentCreate.tpl.html'
		}


	}

})();