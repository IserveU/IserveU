let DomHelper = require('../../helpers/DomHelper');
let VoteSection = require('../../pages/Motion/VoteSection');

class CommentSection extends VoteSection{

	constructor(){
		super();
		/* Comment lookups */
		this.userComment							= element(by.model('userComment.text'));
		this.userCommentTitle 				= element(by.css("h2.motion_usercomment__title"));
		this.userCommentSaveButton 		= element(by.css("user-comment-create button"));




		/* Create Comment UI */
		this.createCommentHeader	= element(by.css('user-comment-create header'));
		this.submitCommentButton 	= element(by.css('user-comment-create button[type=submit]'));
		this.closeCommentButton 	= element(by.css('user-comment-create button[type=button]'));
	}


	getUserCommentTitle(attr){
		return DomHelper.extractAttribute(this.userCommentTitle,attr);
	}

	getUserComment(attr){
		return DomHelper.extractAttribute(this.userComment,attr);
	}

	setAndSaveUserComment(text){
			this.userComment.sendKeys(text);
			DomHelper.clickBetter(this.userCommentSaveButton);

	}

	sectionActive(name){
			var EC = protractor.ExpectedConditions;
			console.log("comment-list-"+name);
			browser.wait(EC.visibilityOf($("comment-list-"+name)), 5000, "Section "+name+" did not become active");
	}





}



module.exports = CommentSection;
