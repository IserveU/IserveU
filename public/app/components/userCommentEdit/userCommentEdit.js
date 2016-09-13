(function() {
	
	angular
		.module('iserveu')
		.directive('userCommentEdit', ['Comment', userCommentEdit]);

	function userCommentEdit(Comment) {

		function userCommentEditController() {
			
			var self = this; // global context for this

			self.comment = {}
			self.editing = false;
			self.editComment = editComment;

			function editComment() {
				self.editing = !self.editing;
			}
		}


		function linkMethod(scope, el, attrs, ctrl) {
			var comment = scope.$eval(attrs.comment);
			comment && (function(){ ctrl.comment = comment})();
		}

		return {
			link: linkMethod,
			controller: userCommentEditController,
			controllerAs: 'userCommentEdit',
			templateUrl: 'app/components/userCommentEdit/userCommentEdit.tpl.html'
		}


	}

})();