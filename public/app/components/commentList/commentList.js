(function() {
	
	angular
		.module('iserveu')
		.directive('commentList', ['utils', commentList]);

	function commentList(utils) {


		function commentListController($scope) {

			var self = this;
			
			self.selectedIndex = 0;
			self.count = count;

			function count(objectArray) {
				if(objectArray instanceof Object) {
					return Object.keys(objectArray).length;
				}
			}

			function fetchSelectedIndex(userVote) {
				var vote = userVote || $scope.motion.userVote;
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

			$scope.$watch('motion.userVote', function(vote) {
				if(vote && vote.id){
					fetchSelectedIndex(vote);
				}
			}, true);

			(function init() {
				utils.waitUntil( function() { return !utils.objectIsEmpty( $scope.motion ) }, fetchSelectedIndex );
			})();
		}


		return {
			controller: ['$scope', commentListController],
			controllerAs: 'commentList',
			templateUrl: 'app/components/commentList/commentList.tpl.html'
		}


	}

})();