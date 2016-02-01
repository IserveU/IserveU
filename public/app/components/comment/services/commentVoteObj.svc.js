(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('commentVoteObj', commentVoteObj);

	function commentVoteObj($stateParams, commentvote, commentObj, ToastMessage) {

		var obj = {
			loading: false,
			save: function (id, pos) {
				if(!obj.loading) {

	            obj.loading = true;

	            commentvote
	            	.saveCommentVotes({comment_id: id, position: pos})
		   				.then(function(r){
		   				commentObj.getMotionComments($stateParams.id);
			            obj.loading = false;
		            },function(e){
		                ToastMessage.report_error(e);
			            obj.loading = false;

		            });  
		
	            }
			},
			update: function(id, pos) {
				if(!obj.loading) {

	            obj.loading = true;

				commentvote.updateCommentVotes({id: id, position: pos})
				 	.then(function(r){
						commentObj.getMotionComments($stateParams.id);
			            obj.loading = false;

		            },function(e){
		                ToastMessage.report_error(e);
			            obj.loading = false;

		            }); 

	            }		
			},
			delete: function(id) {
				if(!obj.loading) {

	            obj.loading = true;

				commentvote.deleteCommentVote(id)
					.then(function(r) {
						commentObj.getMotionComments($stateParams.id);
			            obj.loading = false;

					}, function(e) {
						ToastMessage.report_error(e);
			            obj.loading = false;

					});

	            }		
			},
			onclick: function(id, pos, vote) {
				if ( vote.length === 0 )
					obj.save(id, pos);

				for(var i in vote) {
					if (id === vote[i].comment_id) 

						pos === vote[i].position ? 
							obj.delete(vote[i].id) :
							obj.update(vote[i].id, pos);
					else
						obj.save(id, pos);
				}
			},
			buttonClass: function(id, pos, votes){
				for( var i in votes ) {
					if ( id === votes[i].comment_id )
						if ( votes[i].position === 1 && pos == 1) 
							return 'md-primary';
						else if ( votes[i].position === -1 && pos == -1) 
							return 'md-accent';
				}
			},
			iconClass: function(id, pos, votes) {
				if (votes.length === 0)
					return pos == 1 ? 'thumb-up-outline' : 'thumb-down-outline';

				for( var i in votes ) {
					if ( id === votes[i].comment_id )
						if ( pos == 1) 
							return votes[i].position === 1 ? 'thumb-up' : 'thumb-up-outline';
						else if ( pos == -1) 
							return votes[i].position === -1 ?'thumb-down' : 'thumb-down-outline';
				}
			}
		};

		return obj;

	}




})();