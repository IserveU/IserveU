let DomHelper = require('../../helpers/DomHelper');

class ShowPage{

	constructor(){


	}


	get(slug){
    
		if(!slug){
      browser.get('/#/home');
		}

		browser.get('/#/page/'+slug);
    
    browser.sleep(1000);
    
    var button = element(by.css('md-dialog-content button.terms_conditions__button'));
    
    button.isPresent().then(function(result) {
        if ( result ) {
            button.click();
        } else {
          console.log('no terms button');
        }
    });
				
	}

}



module.exports = ShowPage;