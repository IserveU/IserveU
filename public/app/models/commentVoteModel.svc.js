(function() {
	
'use strict';

angular
.module('iserveu')
.factory('CommentVote', 
	['$http',
	 'commentVoteResource',
	 'errorHandler',

function($http, commentVoteResource, errorHandler) {

	function CommentVote(commentVoteData) {

		if(commentVoteData) {
			this.setData(commentVoteData);
		}

		this.agree = {
			type: 'agree',
			value: 1,
			icon: 'thumb-up-outline',
			class: 'md-primary',
			activeIcon: 'thumb-up',
			originalIcon: 'thumb-up-outline',
			isActive: false
		};

		this.disagree = {
			type: 'disagree',
			value: -1,
			icon: 'thumb-down-outline',
			class: 'md-accent',
			activeIcon: 'thumb-down',
			originalIcon: 'thumb-down-outline',
			isActive: false
		};
	}

	CommentVote.prototype = {

		setData: function(commentVoteData) {
			angular.extend(this, commentVoteData);
		},

		setActive: function(type) {

			this[type].isActive = true;
			this[type].icon = this[type].activeIcon;

		},

		castVote: function(comment, type, motion) {

			var otherType = type === 'agree' ? 'disagree' : 'agree';

			var target = this[type];
			var other = this[otherType];

			if(target.isActive) {
				deleteCommentVote(this, target, motion);
			}
			else {
				if(target.isActive || other.isActive) {
					update(this, target, other, motion);				
				} else {
					save(this, target, comment, motion);
				}				
			}

		},

	}

    /*****************************************************************
    *
    *	Private functions.
    *
    ******************************************************************/

	function switchIconClasses(target, otherTarget) {

		target.icon = target.activeIcon;
		otherTarget.icon = otherTarget.originalIcon;
		otherTarget.isActive = !otherTarget.isActive;
	
	}

	function save(commentVote, target, comment, motion) {
		target.icon = target.activeIcon;
		commentVoteResource.saveCommentVotes({

			comment_id: comment.id,
			position: target.value
	
		}).then(function(success){

			commentVote.id = success.id;
			target.icon = target.activeIcon;
			target.isActive = !target.isActive;

			motion.getMotionComments();

		}, function(error){
			target.icon = target.originalIcon;
			errorHandler(error);
		});
	}

	function update(commentVote, target, otherTarget, motion) {
		switchIconClasses(target, otherTarget);
		commentVoteResource.updateCommentVotes({
		
			id: commentVote.id,
			position: target.value
		
		}).then(function(success){

			target.isActive = !target.isActive;
			motion.getMotionComments();


		}, function(error){
			switchIconClasses(otherTarget, target);
			errorHandler(error);
		});
	}

	function deleteCommentVote(commentVote, target, motion) {
		target.icon = target.originalIcon;
		commentVoteResource.deleteCommentVote({
		
			id: commentVote.id
		
		}).then(function(success){

			target.isActive = !target.isActive;
			delete commentVote.id;

			motion.getMotionComments();

		}, function(error){
			target.icon = target.activeIcon;
			errorHandler(error);
		});
	}


	return CommentVote;

}]);


})();

