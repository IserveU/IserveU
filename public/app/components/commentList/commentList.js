(function() {
	
	angular
		.module('iserveu')
		.directive('commentList', [
			'$rootScope',
			'commentVoteResource',
			'utils',
		commentList]);

	function commentList($rootScope, commentVoteResource, utils) {


		function commentListController($scope) {

			var self = this;
			
			self.selectedIndex = 0;
			self.count = utils.count;

			function fetchUserCommentVotes() {
				if(!$rootScope.authenticatedUser) {
					return false;
				}

				commentVoteResource.getUserCommentVotes({user_id: $rootScope.authenticatedUser.id}).then(function(results){
					$scope.commentVoteList = results.data;
				});
			}

			function fetchSelectedIndex(_userVote) {
				var vote = _userVote || $scope.motion._userVote;
				if( !vote || vote.position === 'undefined' ) {
					return;
				} else if( vote.position == 1 ) {
					self.selectedIndex = 2;
				} else if( vote.position == 0 ) {
					self.selectedIndex = 0;
				} else if( vote.position == -1) {
					self.selectedIndex = 0;
				}
			}

			$scope.$watch('motion._userVote', function(vote) {
				if(vote && vote.id){
					fetchSelectedIndex(vote);
				}
			}, true);

			(function init() {
				utils.waitUntil( function() { return !utils.objectIsEmpty( $scope.motion ) }, 
					function fetchItems() {
						fetchSelectedIndex();
						fetchUserCommentVotes();
					});
			})();
		}


		return {
			controller: ['$scope', commentListController],
			controllerAs: 'commentList',
			templateUrl: 'app/components/commentList/commentList.tpl.html'
		}


	}

})();