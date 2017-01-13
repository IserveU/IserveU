let DomHelper = require('../../helpers/DomHelper');
let ShowMotionPage = require('../../pages/Motion/ShowMotionPage');


class VoteSection extends ShowMotionPage{

	constructor(){
		super();

		/* Button lookups */
		this.agreeButton 			= 	element(by.css('.motion_vote_buttons__button--agree'));
		this.abstainButton 			= 	element(by.css('.motion_vote_buttons__button--abstain'));
		this.disagreeButton			= 	element(by.css('.motion_vote_buttons__button--disagree'));

		this.agreeBar 				=	element(by.css('.motion_vote_statusbar__bar--agree'));
		this.disagreeBar 			=	element(by.css('.motion_vote_statusbar__bar--disagree'));
		this.abstainBar 			=	element(by.css('.motion_vote_statusbar__bar--abstain'));

		this.passingStatusIcon 		=	element(by.id('passing_status_icon'));
	}

	clickAgreeButton(){
    var EC = protractor.ExpectedConditions;
    browser.wait(EC.elementToBeClickable(this.agreeButton),10000,"Cannot click agreeButton");
		this.agreeButton.click();
	}

	clickAbstainButton(){
    var EC = protractor.ExpectedConditions;
    browser.wait(EC.elementToBeClickable(this.abstainButton),10000,"Cannot click abstainButton");
		this.abstainButton.click();
	}

	clickDisagreeButton(){
    var EC = protractor.ExpectedConditions;
    browser.wait(EC.elementToBeClickable(this.disagreeButton),10000,"Cannot click disagreeButton");
		this.disagreeButton.click();
	}

	voteRandomWay(){
		var rand = Math.floor(Math.random() * 3);

		switch(rand){
			case 0:
				this.clickAgreeButton();
				break;
			case 1:
				this.clickAbstainButton();
				break;
			case 2:
				this.clickDisagreeButton();
				break;

		}

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

	getPassingStatusIcon(){
		return this.passingStatusIcon;
	}

	getCounts(){
		let self = this;
		let deferred = protractor.promise.defer();

		var counts = {
			agree:0,
			disagree:0,
			abstain:0
		};

		self.getAgreeCount().then(function(value){
			counts.agree = value?value:0;

			self.getDisagreeCount().then(function(value){
				counts.disagree = value;

				self.getAbstainCount().then(function(value){
					counts.abstain = value;

					deferred.fulfill(counts);
				});
			});
		});

		return deferred.promise;
	}

	static getCountFromBar(bar){
		let deferred = protractor.promise.defer()

		bar.isPresent().then(function(isPresent){
   			if(!isPresent){
   				deferred.fulfill(null);
   			} else {
				bar.getAttribute('aria-label').then(function(attr){
					let count = VoteSection.getCountFromLabelString(attr);
					deferred.fulfill(count);
				});
   			}
   		});

   		return deferred.promise;

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
