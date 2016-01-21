(function(){


	'use strict';

	angular
		.module('iserveu')
		.factory('commentObj', commentObj);

	function commentObj($stateParams, $mdToast, comment, ToastMessage) {

		var cObj = {
			comment: null,
			motionComments: {},
			editing: false,
			writing: false,
			loading: false,
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
				});
			},
			submit: function(vote_id, text){
				cObj.loading = true;
				
				var data = {
	                vote_id: vote_id,
	                text: text
	            }

	            comment.saveComment(data).then(function(r) {
	            	cObj.getMotionComments($stateParams.id);
	                ToastMessage.simple("Comment post successful!");
	                cObj.writing = cObj.loading = false;
	            }, function(error){
	                ToastMessage.report_error(error);
	            });    
			},
			editComment: function() {
				console.log('foo');
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
	            var toast = ToastMessage.delete_toast(" comment");
	            $mdToast.show(toast).then(function(response) {
	                if (response == 'ok')
	                    comment.deleteComment(cObj.comment.id).then(function(r) {
							cObj.getMotionComments($stateParams.id);
	                    }); 
	            });
			},
		};

		cObj.getMotionComments($stateParams.id);

		return cObj;
	}

})();