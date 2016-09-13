(function() {
	
	angular
		.module('iserveu')
		.directive('userCommentCreate', ['Comment', 'utils', userCommentCreate]);

	function userCommentCreate(Comment, utils) {

		function userCommentCreateController($scope, $attrs) {
			
			var self = this; // global context for this

			self.comment = {};
			self.writing = false;
			self.writeComment = writeComment;

			function writeComment() {
				self.writing = !self.writing;
				if(utils.objectIsEmpty(self.comment)) {
					self.comment = new Comment();
					$attrs.comment = self.comment;
				}
			}
			
		}

		function linkMethod(scope, el, attrs, ctrl) {
			var comment = scope.$eval(attrs.comment);
			console.log(comment);
			comment && (function(){ ctrl.comment = comment})();
		}


		return {
			link: linkMethod,
			controller: ['$scope', '$attrs', userCommentCreateController],
			controllerAs: 'userCommentCreate',
			templateUrl: 'app/components/userCommentCreate/userCommentCreate.tpl.html'
		}


	}

})();