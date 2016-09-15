(function() {
	
	angular
		.module('iserveu')
		.directive('userComment', 
			['$rootScope',
			 '$interval',
			 'Comment',
			 'commentResource', 
			 'utils',
		userComment]);

	function userComment($rootScope, $interval, Comment, commentResource, utils) {

		function userCommentController($scope) {
		
			var self = this, comments;

			$scope.isEmpty = utils.iobjectIsEmpty;
			$scope.userComment = new Comment();

			function fetchUserComments() {
				commentResource.getUserComments({user_id: $rootScope.authenticatedUser.id}).then(determineCommentExists);
			}

			function determineCommentExists(userComments) {
				
				if(!$scope.motion || !$scope.motion.userVote){
					return false;
				}

				comments = userComments.data || userComments;
				for( var i in comments ) {
					if(comments[i].vote_id === $scope.motion.userVote.id) {
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
					if(!utils.objectIsEmpty( $scope.motion )){
						fetchUserComments();
						$interval.cancel(waitUntil);
					}
				}, 500);
			})();
		}

		return {
			controller: ['$scope', userCommentController],
			templateUrl: 'app/components/userComment/userComment.tpl.html'
		}


	}

})();