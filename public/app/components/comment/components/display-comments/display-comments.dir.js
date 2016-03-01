(function() {

	'use strict';

	angular
		.module('iserveu')
		.directive('displayComments', displayComments);

	/** @ngInject */
	function displayComments($stateParams, $timeout, commentObj, commentVoteObj, voteObj){

		function displayCommentsController($scope) {

			$scope.display = commentObj;
			$scope.vote = voteObj;

			this.obj = commentObj;

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


			$scope.$watch('display.vote.user', function(newValue, oldValue) {
				if( !angular.isUndefined(newValue) )
					// some sort of digest conflict, doesn't work without the slight 
					// offset of the timeout
					if(newValue.motion_id == $stateParams.id)
		            	$timeout(function() {
		                    voteObj.user  = newValue ? newValue : {position: null} ;     
							voteObj.calculateVotes(newValue.motion_id);
		            	}, 100);
			}, true);


		}

		return {
			controller: displayCommentsController,
			controllerAs: 'show',
			templateUrl: 'app/components/comment/components/display-comments/display-comments.tpl.html'
		}

	
	}

})();