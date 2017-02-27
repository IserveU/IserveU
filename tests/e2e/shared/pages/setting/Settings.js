let DomHelper = require('../../helpers/DomHelper');


class Settings{

	constructor() {
		this.cogMenu			= element(by.id("setting_cog"));

		this.cogMenuButtons 	=	{
			"site-administration" 	: element(by.cssContainingText("md-menu-item button","Site Admin")),
			"submit-a-motion" 		: element(by.cssContainingText("md-menu-item button","Submit a")),
			"logout"		 		: element(by.cssContainingText("md-menu-item button","Logout"))
		}
		

	}

	openSettingsSection(menuItem){
		DomHelper.clickBetter(this.cogMenu);
		DomHelper.clickBetter(this.cogMenuButtons[menuItem])
	}
  
  openCogMenu(){
    DomHelper.clickBetter(this.cogMenu);
  }
  
  

}

module.exports = Settings;