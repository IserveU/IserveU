(function() {
	
'use strict';

angular
.module('iserveu')
.factory('Comment', 
	['$http',
	 'commentResource',
	 'ToastMessage',
	 'errorHandler',

function($http, commentResource, ToastMessage, errorHandler) {

	function Comment(commentData) {

		if(commentData) {
			this.setData(commentData);
		}

		this.posting = false;

	}

	Comment.prototype = {

		setData: function(commentData) {
			angular.extend(this, commentData);
		},

		reloadComment: function(commentData) {
			// this = angular.copy(commentData);
		},

		submit: function(voteId, motion) {
			submit(this, voteId, motion)
		},

		update: function(motion) {
			update(this, motion);
		},

		delete: function(motion) {
			deleteComment(this, motion);
		}

	}

	/**
	*	Private functions
	*
	*/

	function submit(comment, voteId, motion) {

		var self = comment;
		self.posting = true;

		commentResource.saveComment({
			vote_id: voteId,
			text: self.text
		}).then(function(success){
			self.posting = false;
			motion.getMotionComments(motion.id);
		}, function(error){
			errorHandler(error);
			self.posting = false;

		});
	}

	function update(comment, motion) {	

		var self = comment;
		commentResource.updateComment({
			id: self.id,
			text: self.text
		}).then(function(success){
			self.posting = false;
			motion.getMotionComments(motion.id);
		}, function(error){
			errorHandler(error);
			self.posting = false;

		});
	}

	function deleteComment(comment, motion) {			
		
		var self = comment;
		ToastMessage.destroyThisThenUndo("comment", function() {
            commentResource.deleteComment(self.id).then(function(results) {
				motion.getMotionComments(motion.id);
            }); 
		}, function() {
			commentResource.restoreComment(self.id).then(function(results){
				motion.getMotionComments(motion.id);
			})
		});
	}

	return Comment;

}]);


})();

