(function() {

	'use strict';

	angular
		.module('iserveu')
		.service('CommentVoteService', CommentVoteService);

	function CommentVoteService($rootScope, $stateParams, commentvote, ToastMessage) {

		var vm = this;

	    function checkCommentVotes (comment, position, thisUsersCommentVotes) {
            //put in switch statment maybe
            if(comment.this_users_comment_vote  == null){
                saveCommentVotes(comment.id, position);
            }
            if(comment.this_users_comment_vote == 1){
                angular.forEach(thisUsersCommentVotes, function(comment_votes, key){
                    if(comment_votes.comment_id == comment.id){
                        if(position == 1) {
                           commentvote.deleteCommentVote(comment_votes.id);
                        }
                    updateCommentVotes(comment_votes.id, -1);
                   }
                });
            }
            if(comment.this_users_comment_vote == -1){
                angular.forEach(thisUsersCommentVotes, function(comment_votes, key){
                    if(comment_votes.comment_id == comment.id){
                        if(position == -1) {
                            commentvote.deleteCommentVote(comment_votes.id);
                        }
                    updateCommentVotes(comment_votes.id, 1);
                    }
                });
            }
        }

        function saveCommentVotes (id, position) {
            var data = {
                comment_id:id,
                position:position
            }
            
            commentvote.saveCommentVotes(data).then(function(result){
                $rootScope.$emit('getMotionComments', {id: $stateParams.id});
            },function(error){
                ToastMessage.report_error(error);
            }); 
        }

        function updateCommentVotes (id, position) {
            var data = {
                id:id,
                position:position
            }

            commentvote.updateCommentVotes(data).then(function(result){
                $rootScope.$emit('getMotionComments', {id: $stateParams.id});
            },function(error){
                ToastMessage.report_error(error);
            }); 

        }

        function calculate (comments, userCommentVotes) {
            angular.forEach(comments, function(comment) {
                angular.forEach(userCommentVotes, function(commentVote) {
                    if(comment.id==commentVote.comment_id){
                        comment['this_users_comment_vote'] = commentVote.position;
                    }
                });
            });
        }

        return {
        	saveCommentVotes: saveCommentVotes,
        	updateCommentVotes: updateCommentVotes,
        	calculate: calculate,
        	checkCommentVotes: checkCommentVotes
        }

	}


}());
 		