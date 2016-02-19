(function(){


	'use strict';

	angular
		.module('iserveu')
		.factory('commentObj', commentObj);

	function commentObj($stateParams, comment, ToastMessage) {

		var cObj = {
			comment: null,
			comments: { agree: null, disagree: null, vote: null },
			editing: false,
			writing: false,
			posting: false,
			getUserComment: function(r){
				cObj.comment = r;
				if(cObj.comment && cObj.comment.text)
					cObj.writing = false;
				else
					cObj.writing = true;
			},
			getMotionComments: function(id){
				comment.getMotionComments(id).then(function(r) {
					cObj.getUserComment(r.thisUsersComment);
					cObj.comments.agree = r.agreeComments;
					cObj.comments.disagree = r.disagreeComments;
					cObj.comments.vote = r.thisUsersCommentVotes;
				});
			},
			submit: function(vote_id, text){
				cObj.posting = true;

				var data = {
	                vote_id: vote_id,
	                text: text
	            }

	            comment.saveComment(data).then(function(r) {
	            	cObj.getMotionComments($stateParams.id);
	                ToastMessage.simple("Comment post successful!");
	                cObj.writing = cObj.posting = false;
	            }, function(error){
	                ToastMessage.report_error(error);
	            });    
			},
			editComment: function() {
				cObj.editing = !cObj.editing;
			},
			update: function(text) {
	            var d = {
	                id: cObj.comment.id,
	                text: cObj.comment.text
	            }
	            comment.updateComment(d).then(function(r) {
	            	cObj.getMotionComments($stateParams.id);
	                ToastMessage.simple("Commment updated!");
	            });
			},
			delete: function(){
				ToastMessage.destroyThis("comment", function() {
                    comment.deleteComment(cObj.comment.id).then(function(r) {
						cObj.getMotionComments($stateParams.id);
						ToastMessage.simple("Comment deleted.")
                    }); 
				});
			},
		};

		cObj.getMotionComments($stateParams.id);

		return cObj;
	}

})();