let DomHelper = require('../helpers/DomHelper');

class MotionPage{

	constructor(){
		/* Motion Details */
		this.motionTitle 			= element(by.css('h1.motion__title'));
		this.motionText 			= element(by.binding('motion.text'));
		this.motionFiles 			= element.all(by.repeater('file in motion.motionFiles'))


		/* Button lookups */
		this.agreeButton 			= element(by.id('agree_button'));
		this.abstainButton 			= element(by.id('abstain_button'));
		this.disagreeButton			= element(by.buttonText('disagree_button'));

		/* Comment lookups */
		this.userComment			= element(by.model('userComment.text'));

		/* Create Comment UI */
		this.createCommentHeader	= element(by.css('user-comment-create header'));
		this.submitCommentButton 	= element(by.css('user-comment-create button[type=submit]'));
		this.closeCommentButton 	= element(by.css('user-comment-create button[type=button]'));
	}

	clickAgreeButton(){
		this.agreeButton.click();
	}

	clickAbstainButton(){
		this.abstainButton.click();
	}

	clickDisagreeButton(){
		this.disagreeButton.click();
	}

	get(slug){
		if(slug){
			return browser.get('#/motion/'+slug);
		}

		return browser.get('#/motion/a-published-motion'); //The database seeder suite
	}

	getTitle(attr){
		return DomHelper.extractAttribute(this.motionTitle,attr);
	}

	getText(attr){
		return DomHelper.extractAttribute(this.motionText,attr);
	}

	getFile(title){
		if(!title){
			return this.motionFiles;
		}

		return this.motionFiles.filter(function(elem, index) {
		  return elem.getText().then(function(text) {
		    return text === title;
		  });
		}).first()
	}
}



module.exports = MotionPage;