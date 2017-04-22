let DomHelper = require('../../helpers/DomHelper');

class ShowPage{

	constructor(){


	}


	get(slug){
    
		if(!slug){
      return browser.get('/#/home');
		}

		return browser.get('/#/page/'+slug);
				
	}

}



module.exports = ShowPage;