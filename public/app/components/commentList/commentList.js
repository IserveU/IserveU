(function() {

	angular
		.module('iserveu')
		.directive('commentList', [
			'$rootScope',
			'commentVoteResource',
			'utils',
			commentList
		]);

	function commentList($rootScope, commentVoteResource, utils) {


		function commentListController($scope) {

			var self = this;

			self.selectedIndex = 0;
			self.count = utils.count;

			function fetchUserCommentVotes() {

				if($rootScope.authenticatedUser === undefined || $rootScope.authenticatedUser === null || $rootScope.authenticatedUser === "" ) {
					return false;
				}
        
				$scope.userCommentVote.then(function(results) {
					$scope.commentVoteList = results.data;
				});
			}

			function fetchSelectedIndex(_userVote) {
				var vote = _userVote || $scope.motion._userVote;
				if (!vote || vote.position === 'undefined') {
					return;
				} else if (vote.position === 1) {
					self.selectedIndex = 0;
				} else if (vote.position === 0) {
					self.selectedIndex = 1;
				} else if (vote.position === -1) {
					self.selectedIndex = 2;
				}
			}

			$scope.$watch('motion._userVote', function(vote) {
				if (vote && vote.id) {
					fetchSelectedIndex(vote);
				}
			}, true);

			(function init() {
				utils.waitUntil(function() {
						return !utils.objectIsEmpty($scope.motion)
					},
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