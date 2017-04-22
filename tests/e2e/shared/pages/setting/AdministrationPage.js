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
		DomHelper.clickBetter(this.tabButtons[name.toLowerCase()]);
	}

	openSection(text){
		DomHelper.clickBetter(element(by.cssContainingText('.md-button',text)));
	}


}


module.exports = AdministrationPage;