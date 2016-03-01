(function(){


	'use strict';

	angular
		.module('iserveu')
		.factory('commentObj', commentObj);

	/** @ngInject */
	function commentObj($stateParams, comment, ToastMessage) {

		var factory = {
			comment: null,
			comments: { agree: null, disagree: null, vote: null },
			editing: false,
			writing: false,
			posting: false,
			getUserComment: function(r){
				this.comment = r;
				if(this.comment && this.comment.text)
					this.writing = false;
				else
					this.writing = true;
			},
			getMotionComments: function(id){
				comment.getMotionComments(id).then(function(r) {
					factory.getUserComment(r.thisUsersComment);
					factory.comments.agree = r.agreeComments;
					factory.comments.disagree = r.disagreeComments;
					factory.comments.vote = r.thisUsersCommentVotes;
				});
			},
			submit: function(vote_id, text){
				this.posting = true;

	            comment.saveComment({
	                vote_id: vote_id,
	                text: text
	            }).then(function(r) {
	                ToastMessage.simple("Comment post successful!");
	            	factory.getMotionComments($stateParams.id);
	                factory.writing = factory.posting = false;
	            }, function(e){ ToastMessage.report_error(e); });    
			},
			write: function() {
				this.writing = !this.writing;
			},
			editComment: function() {
				factory.editing = !factory.editing;
			},
			update: function(text) {
	            var d = {
	                id: factory.comment.id,
	                text: factory.comment.text
	            }
	            comment.updateComment(d).then(function(r) {
	            	factory.getMotionComments($stateParams.id);
	                ToastMessage.simple("Commment updated!");
	            });
			},
			delete: function(){
				ToastMessage.destroyThis("comment", function() {
                    comment.deleteComment(factory.comment.id).then(function(r) {
						factory.getMotionComments($stateParams.id);
						ToastMessage.simple("Comment deleted.")
                    }); 
				});
			},
		};

		factory.getMotionComments($stateParams.id);

		return factory;
	}

})();