(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.directive('commentVote', ['CommentVote', commentVote]);

	function commentVote(CommentVote) {

		function commentVoteController() {

			var self = this;

			self.button = new CommentVote();
			self.loadCommentVotes = loadCommentVotes;

			function loadCommentVotes(comment, thisUsersCommentVotes) {

				for(var i in thisUsersCommentVotes) {
					if(thisUsersCommentVotes[i].comment_id === comment.id) {
						var type = thisUsersCommentVotes[i].position == 1 ? 'agree' : 'disagree';
						self.button.setData(thisUsersCommentVotes[i]);
						self.button.setActive(type);
					};
				};
			}

		}

		return {
			controller: commentVoteController,
			controllerAs: 'commentVote',
			templateUrl: 'app/components/commentVote/commentVote.tpl.html'
		}


	}

})();