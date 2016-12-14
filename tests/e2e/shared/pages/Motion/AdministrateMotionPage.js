let DomHelper = require('../../helpers/DomHelper');
let ShowMotionPage = require('../../pages/Motion/ShowMotionPage');

class AdministrateMotionPage extends ShowMotionPage{

	constructor(){
		super();

		/* FAB UI */
		this.fabMenu					= element(by.tagName('md-fab-trigger'));		
		this.createFabButton			= element(by.id('create_new_motion'));
		this.editFabButton				= element(by.id('edit_this_motion'));
		this.deleteFabButton			= element(by.id('delete_this_motion'));		

		/* Form Buttons */
		this.saveButton 				= element(by.css('.create-motion__button button[type=submit]'));
		this.cancelButton 				= element(by.css('.create-motion__button button[type=button]'));

		/* Toasts */
		this.deleteMotionConfirmation	= element(by.css('md-toast button'));

	}

	createMotion(){
		return browser.get('#/create-motion');
	}

	editMotion(slug){
		return browser.get('#/edit-motion/'+slug);
	}

	clickCreateMotion(){
		this.fabMenu.click();
		this.createFabButton.click();
	}

	clickEditMotion(){
		this.fabMenu.click();
		this.editFabButton.click();
	}
	
	clickDeleteMotion(){
		this.fabMenu.click();
		this.deleteFabButton.click();
	}

	clickDeleteMotionConfirmation(){
		this.deleteMotionConfirmation.click();
	}

	getDeleteMotionConfirmation(){
		return this.deleteMotionConfirmation;
	}

	clickCancelButton(){
		this.fabMenu.click();
		this.deleteFabButton.click();
	}
	
	getSaveButton(){
		return this.saveButton;
	}


}



module.exports = AdministrateMotionPage;