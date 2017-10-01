(function() {

	angular
		.module('iserveu')
		.directive('userComment',
			['$rootScope',
			 '$interval',
			 'Comment',
			 'CommentResource',
			 'Utils',
		userComment]);

	function userComment($rootScope, $interval, Comment, CommentResource, Utils) {

		function userCommentController($scope) {

			var self = this, comments;

			$scope.isEmpty = Utils.iobjectIsEmpty;
			$scope.userComment = new Comment();

			function fetchUserComments() {
				CommentResource.getUserComments({user_id: $rootScope.authenticatedUser.id}).then(determineCommentExists);
			}

			function determineCommentExists(userComments) {

				if(!$scope.motion || !$scope.motion._userVote){
					return false;
				}

				comments = userComments.data || userComments;
				for( var i in comments ) {
					if(comments[i].motionId === $scope.motion.id) {
						$scope.userComment.setData(comments[i]);
						$scope.userComment.exists = true;
					}
				}
			}

			$scope.$watch('motion.motionComments', function(value, oldValue) {
				if( value !== undefined && $rootScope.authenticatedUser) {
					fetchUserComments();
				}
			}, true);

			(function init() {
				if(!$rootScope.authenticatedUser)
					return false;

				var waitUntil = $interval(function() {
					if(!Utils.objectIsEmpty( $scope.motion )){
						fetchUserComments();
						$interval.cancel(waitUntil);
					}
				}, 500);
			})();
		}

		return {
			controller: ['$scope', userCommentController],
			templateUrl: 'app/components/user/userComment/userComment.tpl.html'
		}


	}

})();
