(function() {
	
	angular
		.module('iserveu')
		.directive('userCommentCreate', ['Comment', 'utils', userCommentCreate]);

	function userCommentCreate(Comment, utils) {

		function userCommentCreateController($scope, $attrs) {
			
			var self = this; // global context for this

			self.writing = false;
			self.writeComment = writeComment;

			function writeComment() {
				self.writing = !self.writing;
			}
		}

		return {
			controller: ['$scope', '$attrs', userCommentCreateController],
			controllerAs: 'userCommentCreate',
			templateUrl: 'app/components/userCommentCreate/userCommentCreate.tpl.html'
		}


	}

})();