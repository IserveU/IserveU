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


		this.passingStatusIcon 			=	element(by.id('passing_status_icon'));
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
			counts.agree = value;

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
   				deferred.fulfill(0);
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