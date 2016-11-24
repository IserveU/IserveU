let DomHelper = require('../../helpers/DomHelper');
let ShowMotionPage = require('../../pages/Motion/ShowMotionPage');

class CommentSection extends ShowMotionPage{

	constructor(){
		super();
		/* Comment lookups */
		this.userComment			= element(by.model('userComment.text'));

		/* Create Comment UI */
		this.createCommentHeader	= element(by.css('user-comment-create header'));
		this.submitCommentButton 	= element(by.css('user-comment-create button[type=submit]'));
		this.closeCommentButton 	= element(by.css('user-comment-create button[type=button]'));
	}

}



module.exports = CommentSection;