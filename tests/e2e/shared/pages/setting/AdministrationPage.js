let DomHelper = require('../../helpers/DomHelper');
let Settings = require('./Settings');

class AdministrationPage extends Settings{

	constructor(){
		super();

		this.tabButtons = {
			"content" 	:	element(by.cssContainingText('md-tab-item span',"Content")),
			"system" 	:	element(by.cssContainingText('md-tab-item span',"System"))
		};


		this.settingsInputs = {
			"authentication" : {
				"required"	:  element(by.model('settingsGlobal.authentication.required'))
 			}

		}
	}


	openTab(name){
		this.tabButtons[name.toLowerCase()].click();
	}

	openSection(text){
		element(by.cssContainingText('.md-button',text)).click();
	}


}


module.exports = AdministrationPage;