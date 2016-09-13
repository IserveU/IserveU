(function() {
	
'use strict';

angular
.module('iserveu')
.factory('Comment', 
	['$http',
	 'commentResource',
	 'ToastMessage',

function($http, commentResource, ToastMessage) {

	function Comment(commentData) {

		if(commentData) {
			this.setData(commentData);
		}

		this.posting = false;
		this.exists  = false;

	}

	Comment.prototype = {

		setData: function(commentData) {
			angular.extend(this, commentData);
		},

		reloadComment: function(commentData) {
			// this = angular.copy(commentData);
		},

		submit: function(motion) {
			submit(this, motion)
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

	function submit(comment, motion) {

		var self = comment;
		self.posting = true;

		commentResource.saveComment({
			vote_id: motion.userVote.id,
			text: self.text
		}).then(function(success){
			self.posting = false;
			self.exists  = true;
			console.log(self);
			
			motion.getMotionComments(motion.id);
		}, function(error){
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
			self.exists  = true;
			console.log(self);
			motion.getMotionComments(motion.id);
		}, function(error){
			self.posting = false;
		});
	}

	function deleteComment(comment, motion) {			
		
		var self = comment;
		ToastMessage.destroyThisThenUndo("comment", function() {
            commentResource.deleteComment(self.id).then(function(results) {
				motion.getMotionComments(motion.id);
				self.exists = false;
            });
            self.setData(null);
		}, function() {
			commentResource.restoreComment(self.id).then(function(results){
				motion.getMotionComments(motion.id);
			})
		});
	}

	return Comment;

}]);


})();

