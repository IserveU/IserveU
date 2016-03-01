(function(){

	'use strict';

	angular
		.module('iserveu')
		.directive('commentOnMotion', commentOnMotion);

	/** @ngInject */
	function commentOnMotion($stateParams, commentObj, voteObj, motionObj) {

		function commentController($scope) {

			$scope.create = commentObj;
			$scope.vote = voteObj;

			$scope.$watch(voteObj.user, function(vote) {
				$scope.vote.user = vote;
			});
		}

		return {
			controller: commentController,
			templateUrl: 'app/components/comment/partials/comment.tpl.html'
		}
	}

})();