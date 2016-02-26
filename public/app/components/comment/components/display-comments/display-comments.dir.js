(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('displayComments', displayComments);

	/** @ngInject */
	function displayComments(commentObj, commentVoteObj, voteObj){

		function displayCommentsController() {

			this.obj = commentObj;
			this.vote = voteObj;
			this.commentVote = commentVoteObj;

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
			controller: displayCommentsController,
			controllerAs: 'dc',
			templateUrl: 'app/components/comment/components/display-comments/display-comments.tpl.html'
		}

	
	}

})();