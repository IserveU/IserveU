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

			function determineCommentExists(userComments) {
				if(!$scope.motion || !$scope.motion.userVote){
					return false;
				}

				comments = comments || userComments;

				for( var i in comments ) {
					if(comments[i].vote_id === $scope.motion.userVote.id) {
						self.comment = new Comment(comments[i]);
						self.comment.exists = true;
						console.log(self.comment);
					}
				}
			}

			$scope.$watch('motion.motionComments', function(value, oldValue) {

				console.log('userComment ccomment exists changes ');
				if( value !== undefined ) {
					commentResource.getUserComments({user_id: $rootScope.authenticatedUser.id}).then(function(results){
						determineCommentExists(results);
					});
				}
			}, true);

			(function init() {
				if(!$rootScope.authenticatedUser)
					return false;

				var waitUntil = $interval(function() {
					if(!utils.objectIsEmpty( $scope.motion )){
						commentResource.getUserComments({user_id: $rootScope.authenticatedUser.id}).then(function(results){
							determineCommentExists(results);
						});
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