(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.directive('commentVote', [
			'$rootScope',
			'CommentVote',
			'commentVoteResource',
			'utils',
		commentVote]);

	function commentVote($rootScope, CommentVote, commentVoteResource, utils) {

		function commentVoteController($scope, $attrs) {

			var self = this;

			self.button = new CommentVote();

			function fetchUserCommentVotes() {
				commentVoteResource.getUserCommentVotes({user_id: $rootScope.authenticatedUser.id}).then(renderActiveCommentVotes);
			}

			function renderActiveCommentVotes(res) {
				var comment_votes = res.data || res,
				 	comment_id    = $scope.$eval($attrs.commentId);

				for(var i in comment_votes) {
					if( comment_votes[i].comment_id == comment_id){
						self.button.setData( comment_votes[i] );
						self.button.setActive( comment_votes[i].position );
					}
				}
			}


			$scope.$watch('motion.motionComments', function(value, oldValue) {
				if( value !== undefined && $rootScope.authenticatedUser) {
					fetchUserCommentVotes();
				}
			}, true);
	

			(function init() {
				if(!$rootScope.authenticatedUser) {
					return false;
				}

				utils.waitUntil(function scopeDependenciesAreInstantiated(){ 
					return !utils.objectIsEmpty($scope.motion) && ( $scope.$parent && $scope.$parent.$parent && !utils.objectIsEmpty( $scope.$parent.$parent.commentVoteList )); 
				}, fetchUserCommentVotes);

			})();
		}

		return {
			controller: ['$scope', '$attrs', commentVoteController],
			controllerAs: 'commentVote',
			templateUrl: 'app/components/commentVote/commentVote.tpl.html'
		}


	}

})();