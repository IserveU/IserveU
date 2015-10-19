(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.directive('commentVote', commentVote);

	function commentVote($compile) {

		function controllerMethod($state, $timeout, comment) {
        	
        	var vm = this;
	        vm.position = $state.current.data.userVote.position;
  		}	

		function linkMethod(scope, element, attrs, ctrl) {

			attrs.$observe('votePosition', function(value) {

				var agree_comments = angular.element(document.getElementById('agreed-comment-vote-col'));
				var disagree_comments = angular.element(document.getElementById('disagreed-comment-vote-col'));

				if(value == 1){
					disagree_comments.remove();
				}
				if(value == -1 || value == 0){
					agree_comments.remove();
				}

			});

	      attrs.$observe('openForVoting', function(value){
	        if(value == 'false'){
	            element.remove();
	        }
	      });

		}


		return {
		    controller: controllerMethod,
		    controllerAs: 'comment_vote',
		    bindToController: true,
			link: linkMethod,
			templateUrl: 'app/components/comment/comment_vote/comment_vote.tpl.html'
		}

	}
}());