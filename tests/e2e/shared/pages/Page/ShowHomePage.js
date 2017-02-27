let DomHelper = require('../../helpers/DomHelper');
let ShowPage = require('../../pages/Page/ShowPage');

class ShowHomePage extends ShowPage{

	constructor(){
    super();

    /* Home Details */
		this.homeIntroduction 			= element(by.tagName('home-introduction'));
		this.topMotions        			= element(by.tagName('top-motions'));
		this.topComments       			= element(by.tagName('top-comments'));
    
    this.yourVotes        			= element(by.tagName('my-votes'));
    this.yourComments      			= element(by.tagName('my-comments'));
	}

}


module.exports = ShowHomePage;