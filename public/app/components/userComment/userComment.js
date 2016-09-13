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


			function fetchUserComments() {
				commentResource.getUserComments({user_id: $rootScope.authenticatedUser.id}).then(determineCommentExists);
			}

			function determineCommentExists(userComments) {
				if(!$scope.motion || !$scope.motion.userVote){
					return false;
				}
				comments = comments || userComments.data;
				for( var i in comments ) {
					if(comments[i].vote_id === $scope.motion.userVote.id) {
						self.comment = new Comment(comments[i]);
						self.comment.exists = true;
						console.log(self.comment);
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
			controllerAs: 'userComment',
			templateUrl: 'app/components/userComment/userComment.tpl.html'
		}


	}

})();