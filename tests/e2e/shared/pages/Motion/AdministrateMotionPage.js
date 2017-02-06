let DomHelper = require('../../helpers/DomHelper');
let ShowMotionPage = require('../../pages/Motion/ShowMotionPage');

class AdministrateMotionPage extends ShowMotionPage{


	constructor(){
		super();

		/* FAB UI */
		this.fabMenu				    	= element(by.tagName('md-fab-trigger'));
		this.createFabButton			= element(by.id('create_new_motion'));
		this.editFabButton				= element(by.id('edit_this_motion'));
		this.deleteFabButton			= element(by.id('delete_this_motion'));

		/* Form Buttons */
		this.saveButton 				= element(by.css('.create-motion__button button[type=submit]'));
		this.cancelButton 				= element(by.css('.create-motion__button button[type=button]'));

		/* Toasts */
		this.deleteMotionConfirmation	= element(by.buttonText('Yes'));


	}

	createMotion(){
		return browser.get('#/create-motion');
	}

	editMotion(slug){
		return browser.get('#/edit-motion/'+slug);
	}

	clickCreateMotion(){
		var EC = protractor.ExpectedConditions;

		DomHelper.clickBetter(this.fabMenu); //.click();
		browser.wait(EC.elementToBeClickable(this.createFabButton), 10000,"Menu did not drop down");
		this.createFabButton.click();
	}

	clickEditMotion(){
		var EC = protractor.ExpectedConditions;

		DomHelper.clickBetter(this.fabMenu); //.click();
		browser.wait(EC.elementToBeClickable(this.editFabButton), 5000,"Menu did not drop down");
		this.editFabButton.click();
	}

	clickDeleteMotion(){
		var EC = protractor.ExpectedConditions;

		this.fabMenu.click();
		browser.wait(EC.elementToBeClickable(this.deleteFabButton), 5000,"Menu did not drop down");
		this.deleteFabButton.click();
	}

	clickDeleteMotionConfirmation(){
		var EC = protractor.ExpectedConditions;
		browser.wait(EC.elementToBeClickable(this.deleteMotionConfirmation), 5000,"Confirmation box did not show");

		this.deleteMotionConfirmation.click();
	}

	getDeleteMotionConfirmation(){
		return this.deleteMotionConfirmation;
	}


	getSaveButton(){
		return this.saveButton;
	}

}



module.exports = AdministrateMotionPage;
