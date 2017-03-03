let DomHelper = require('../../helpers/DomHelper');
let VoteSection = require('../../pages/Motion/VoteSection');

class CommentSection extends VoteSection{

	constructor(){
		super();

		this.EC = protractor.ExpectedConditions;

		/* Comment lookups */
		this.userComment									= element(by.model('userComment.text'));
		this.userCommentTitle 						= element(by.css("h2.motion_usercomment__title"));
		this.userCommentSaveButton 				= element(by.css("user-comment-create button"));
    
    //Existing user comments
    this.userCommentEditButton 				= element(by.css("#user-comment-edit"));
    this.userCommentDeleteButton 				= element(by.css("#user-comment-delete"));
    this.userCommentUpdateButton 				= element(by.css("#user-comment-update"));
    this.userCommentUndoButton 				= element(by.css("#user-comment-undo"));



		this.agreeCommentListButton 		= element(by.css('md-tab-item:first-of-type'));
    this.disagreeCommentListButton 		= element(by.css('md-tab-item:last-of-type'));

		this.agreeComments				 				= element(by.repeater("comment in motion.motionComments.agreeComments"));


		/* Create Comment UI */
		this.createCommentHeader					= element(by.css('user-comment-create header'));
		this.submitCommentButton 					= element(by.css('user-comment-create button[type=submit]'));
		this.closeCommentButton 					= element(by.css('user-comment-create button[type=button]'));
	}


	getUserCommentTitle(attr){
		return DomHelper.extractAttribute(this.userCommentTitle,attr);
	}

	getUserComment(attr){
		return DomHelper.extractAttribute(this.userComment,attr);
	}

	setAndSaveUserComment(text){
      DomHelper.canInteractCheck(this.userComment);
			this.userComment.sendKeys(text);
			DomHelper.clickBetter(this.userCommentSaveButton);
	}

  editAndSaveUserComment(text){
      DomHelper.clickBetter(this.userCommentEditButton);
      this.userComment.clear();
			this.userComment.sendKeys(text);
			DomHelper.clickBetter(this.userCommentUpdateButton);
	}


	expectSectionActive(name){
			if(Array.isArray(name)){
				name.forEach(function(item){
						me.expectSectionActive(item);
				});
				return this;
			}

			console.log("comment-list-"+name);
			browser.wait(this.EC.visibilityOf($("comment-list-"+name)), 5000, "Section "+name+" did not become active");
	}

	expectSectionInactive(name){
			let me = this;
			if(Array.isArray(name)){
				name.forEach(function(item){
						me.expectSectionInactive(item);
				});
				return this;
			}

			console.log("comment-list-"+name);
			browser.wait(this.EC.invisibilityOf($("comment-list-"+name)), 5000, "Section "+name+" stayed active");
	}

	expectCommentListContainsComment(listName,text){
		var EC = protractor.ExpectedConditions;
		let list = element(by.repeater("comment in motion.motionComments."+listName+"Comments")); //.column('comment.text'));//.column('comment.text'));

		if(!Array.isArray(text)){
					browser.wait(EC.textToBePresentInElement(list, text), 5000, "The text ("+text+ ") is not visible in the element");
					return this;
		}

		text.forEach(function(item){
				browser.wait(EC.textToBePresentInElement(list, item), 5000, "The text ("+item+ ") is not visible in the element");
		});
	}
  
  /** This will probably go into some utilty class in a refactor */
  voteAndWriteAComment(text){
    let vm = this;
    this.get('a-commented-on-motion');
    this.voteRandomWay();
    this.userCommentEditButton.isPresent().then(function(result){
      if(result){
        vm.editAndSaveUserComment(text)
      } else {
        vm.setAndSaveUserComment(text)
      }
    });
  }

	clickAgreeSection(){
			DomHelper.clickBetter(this.agreeCommentListButton);
	}
  
  clickDisagreeSection(){
			DomHelper.clickBetter(this.disagreeCommentListButton);
	}
}



module.exports = CommentSection;
