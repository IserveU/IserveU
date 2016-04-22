(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('displayComments', [
			'$stateParams', '$timeout', 'commentObj', 'commentVoteObj', 'voteObj',
			displayComments]);

	/** @ngInject */
	function displayComments($stateParams, $timeout, commentObj, commentVoteObj, voteObj){

		function displayCommentsController($scope) {

			$scope.display = commentObj;
			$scope.vote = voteObj;
			$scope.commentVote = commentVoteObj;

			this.obj = commentObj;


			this.formatDate = formatDate;
			function formatDate(d){
				if(d.created_at.diff !== d.updated_at.diff)
					return "edited " + d.updated_at.diff;
				// else if (d.created_at.date > 3 days )
				// return d.created_at.alpha;
				else
					return d.created_at.diff;
			}

		}

		return {
			controller: ['$scope', displayCommentsController],
			controllerAs: 'show',
			templateUrl: 'app/components/comment/components/display-comments/display-comments.tpl.html'
		}

	
	}

})();