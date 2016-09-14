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


			// $scope.$watch(function() { return commentVoteResource._userCommentVoteIndex;}, function(_index, _oldIndex) {

			// 	console.log('scope watch comment vote resource triggered');

			// console.log($scope.$parent.$parent.commentVoteList);

			// 	if( _index && !utils.objectIsEmpty(_index) ) {

			// 	console.log('scope watch comment vote index not empty');

			// 		console.log(_index);
			// 	}
			// });


			(function init() {
				if(!$rootScope.authenticatedUser) {
					return false;
				}

				utils.waitUntil(function(){ return !utils.objectIsEmpty($scope.motion) && !utils.objectIsEmpty( $scope.$parent.$parent.commentVoteList ); }, fetchUserCommentVotes );

			})();


		}

		return {
			controller: ['$scope', '$attrs', commentVoteController],
			controllerAs: 'commentVote',
			templateUrl: 'app/components/commentVote/commentVote.tpl.html'
		}


	}

})();