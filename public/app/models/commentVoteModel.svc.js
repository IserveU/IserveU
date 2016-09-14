(function() {
	
'use strict';

angular
.module('iserveu')
.factory('CommentVote', [
	'commentVoteResource',

function(commentVoteResource) {

	function CommentVote(commentVoteData) {

		if(commentVoteData) {
			this.setData(commentVoteData);
		}

		this.agree = {
			type: 'agree',
			value: 1,
			selectedIcon: 'thumb-up-outline',
			class: 'md-primary',
			activeIcon: 'thumb-up',
			defaultIcon: 'thumb-up-outline',
			isActive: false
		};

		this.disagree = {
			type: 'disagree',
			value: -1,
			selectedIcon: 'thumb-down-outline',
			class: 'md-accent',
			activeIcon: 'thumb-down',
			defaultIcon: 'thumb-down-outline',
			isActive: false
		};
	}

	CommentVote.prototype = {

		setData: function(commentVoteData) {
			angular.extend(this, commentVoteData);
		},

		setActive: function(type) {
			type = stringifyPosition(type);
			this[type].isActive = true;
			this[type].selectedIcon = this[type].activeIcon;
		},

		setDefault: function(type) {
			type = stringifyPosition(type);
			this[type].isActive = false;
			this[type].selectedIcon = this[type].defaultIcon;
		},

		castVote: function(comment_id, pos) {

			var type    = stringifyPosition( pos );
			var oldType = stringifyPosition( pos *= -1 );

			if( this[type].isActive ) {
				destroy(this, type);
			} else if( this[oldType].isActive && this.id  ) {
				update( this, type, oldType );
			} else {
				create( this, type, comment_id );
			}
		}
	}

    /*****************************************************************
    *
    *	Private functions.
    *
    ******************************************************************/

    function numberifyPosition(pos) {
    	if(!angular.isString(pos) || pos.length <= 2) {
			return +pos;
		}
		return pos == 'agree' ? 1 : -1;
    }

	function stringifyPosition(pos) {
		if(angular.isString(pos) && pos.length > 2) {
			return pos;
		}
		return pos === 1 ? 'agree' : 'disagree';
	}

	function create(self, type, comment_id) {
		self.setActive(type); // either put here or above.
		commentVoteResource.saveCommentVote({
			comment_id: comment_id,
			position: numberifyPosition(type)
		}).then(function(success){
			self.id  = success.id;
		}, function(error){
			self.setDefault(type);
		});
	}

	function update(self, type, oldType) {

		self.setActive(type);
		self.setDefault(oldType);

		commentVoteResource.updateCommentVote({
			id: self.id,
			position: numberifyPosition(type)
		}).then(function(success){}, function(error){
			self.setDefault(type);
		});
	}

	function destroy(self, type) {
		self.setDefault(type);
		commentVoteResource.deleteCommentVote({id: self.id}).then(function(){
			delete self.id;
		}, function(error){
			self.setActive(type);
		});
	}


	return CommentVote;

}]);


})();

