(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('commentVote', [
			'$rootScope',
			'CommentVote',
			'commentVoteResource',
			'utils',
			commentVote
		]);

	function commentVote($rootScope, CommentVote, commentVoteResource, utils) {

		function commentVoteController($scope, $attrs) {

			var self = this;
			self.button = new CommentVote();
			self.position = $attrs.position;
			self.currentVote = 0;
			self.initialVote = 0;

			self.vote = function(id, pos) {

				if (self.button['agree'].isActive) {
					self.currentVote = pos === -1 ? pos : 0;
				} else if (self.button['disagree'].isActive) {
					self.currentVote = pos === 1 ? pos : 0;
				} else {
					self.currentVote = pos;
				}

				self.button.castVote(id, pos);
			}

			function fetchUserCommentVotes() {
				$scope.userCommentVote.then(renderActiveCommentVotes);
				if (self.button.agree.isActive) {
					self.currentVote = 1;
				} else if (self.button.disagree.isActive) {
					self.currentVote = -1;
				} else {
					self.currentVote = 0;
				}

				self.initialVote = self.currentVote;

			}

			function renderActiveCommentVotes(res) {
				var comment_votes = res.data || res,
					comment_id = $scope.$eval($attrs.commentId);

				for (var i in comment_votes) {
					if (comment_votes[i].comment_id === comment_id) {
						self.button.setData(comment_votes[i]);
						self.button.setActive(comment_votes[i].position);
					}
				}
			}


			$scope.$watch('motion.motionComments', function(value, oldValue) {
				if (value !== undefined && $rootScope.authenticatedUser) {
					fetchUserCommentVotes();
				}
			}, true);


			(function init() {
				if (!$rootScope.authenticatedUser) {
					return false;
				}
				fetchUserCommentVotes();
			})();
		}

		return {
			controller: ['$scope', '$attrs', commentVoteController],
			controllerAs: 'commentVote',
			templateUrl: 'app/components/commentVote/commentVote.tpl.html'
		}


	}

})();