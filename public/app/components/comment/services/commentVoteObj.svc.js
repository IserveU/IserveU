(function() {

	'use strict';

	angular
		.module('iserveu')
		.factory('commentVotefactory', [
			'$stateParams', 'commentvote', 'commentfactory', 'ToastMessage',
			commentVotefactory]);

	/** @ngInject */
	function commentVotefactory($stateParams, commentvote, commentfactory, ToastMessage) {

		var factory = {
			loading: false,
			save: function (id, pos) {
				if(!factory.loading) {

	            factory.loading = true;

	            commentvote
	            	.saveCommentVotes({comment_id: id, position: pos})
		   				.then(function(r){
		   				commentfactory.getMotionComments($stateParams.id);
			            factory.loading = false;
		            },function(e){
		                ToastMessage.report_error(e);
			            factory.loading = false;

		            });  
		
	            }
			},
			update: function(id, pos) {
				if(!factory.loading) {

	            factory.loading = true;

				commentvote.updateCommentVotes({id: id, position: pos})
				 	.then(function(r){
						commentfactory.getMotionComments($stateParams.id);
			            factory.loading = false;

		            },function(e){
		                ToastMessage.report_error(e);
			            factory.loading = false;

		            }); 

	            }		
			},
			delete: function(id) {
				if(!factory.loading) {

	            factory.loading = true;

				commentvote.deleteCommentVote(id)
					.then(function(r) {
						commentfactory.getMotionComments($stateParams.id);
			            factory.loading = false;

					}, function(e) {
						ToastMessage.report_error(e);
			            factory.loading = false;

					});

	            }		
			},
			onclick: function(id, pos, vote) {
				if ( vote.length === 0 )
					factory.save(id, pos);

				for(var i in vote) {
					if (id === vote[i].comment_id) 

						pos === vote[i].position ? 
							factory.delete(vote[i].id) :
							factory.update(vote[i].id, pos);
					else
						factory.save(id, pos);
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

		return factory;

	}




})();