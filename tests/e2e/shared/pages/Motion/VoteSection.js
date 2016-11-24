let DomHelper = require('../../helpers/DomHelper');
let ShowMotionPage = require('../../pages/Motion/ShowMotionPage');


class VoteSection extends ShowMotionPage{

	constructor(){
		super();
		
		/* Button lookups */
		this.agreeButton 			= 	element(by.id('agree_button'));
		this.abstainButton 			= 	element(by.id('abstain_button'));
		this.disagreeButton			= 	element(by.id('disagree_button'));

		this.agreeBar 				=	element(by.css('button.motion__votes-statusbar_agree'));
		this.disagreeBar 			=	element(by.css('button.motion__votes-statusbar_disagree'));
		this.abstainBar 			=	element(by.css('button.motion__votes-statusbar_abstain'));

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

	getAgreeCount(){
		return VoteSection.getCountFromBar(this.agreeBar);
	}


	getAbstainCount(){
		return VoteSection.getCountFromBar(this.abstainBar);
	}

	getDisagreeCount(){
		return VoteSection.getCountFromBar(this.disagreeBar);
	}


	static getCountFromBar(bar){

		return bar.isPresent().then(function(isPresent){
   			if(!isPresent){
   				return 0;
   			}

			return bar.getAttribute('aria-label').then(function(attr){
				return VoteSection.getCountFromLabelString(attr);			
			});

   		});

	}


	static getCountFromLabelString(labelString){
		expect(typeof labelString).toBe("string");

		if(!labelString){
			return 0;
		}

		return parseInt(labelString, 10);
	}

}



module.exports = VoteSection;