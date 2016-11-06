var VoteButtons = function() {

	this.voteButtons = element.all(by.repeater('oteButton in motionVoteButtons.buttons'));
    
	this.agreeButton = element.all(by.css('button > motion__votes--button_agree'));
	this.disagreeButton = element.all(by.css('button > motion__votes--button_disagree'));
	this.abstainButton = element.all(by.css('button > motion__votes--button_abstain'));

	this.agreeCounter = element(by.className('motion__votes--statusbar-agree'));
	this.disagreeCounter = element(by.className('motion__votes--statusbar-disagree'));
	this.abstainCoutner = element(by.className('motion__votes--statusbar-abstain'));

	this.deferredAgreeCounter = element(by.className('motion__votes--statusbar-deferred_agree'));
	this.deferredDisagreeCounter = element(by.className('motion__votes--statusbar-deferred_disagree'));
	this.deferredAbstainCounter = element(by.className('motion__votes--statusbar-deferred_abstain'));

	


}


module.exports = VoteButtons;