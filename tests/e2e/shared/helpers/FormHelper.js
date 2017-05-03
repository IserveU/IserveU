let DomHelper = require('./DomHelper');

class FormHelper {

	constructor(submitButton,fillFields) {
		this.submitButton = submitButton;
		this.fillFields(fillFields);

    this.EC = protractor.ExpectedConditions;
	}


	fillFields(content){
		for (let [key, value] of content) {
			let field = element(by.name(key));
      DomHelper.canInteractCheck(field);
      field.sendKeys(value);
		}
	}

	/**
	 * Handles the md-selects and their unusual display format
	 * @param  {String} name [description]
	 * @param  {String} text [description]
	 * @return {[type]}      [description]
	 */

	selectBox(model, text){
		
 		DomHelper.clickBetter(element(by.model(model)));
    
    browser.sleep(1500); //Wait for the box to animate open
    
    let el = element(by.cssContainingText('.md-select-menu-container.md-active md-option > div.md-text', text));
      
    DomHelper.clickBetter(el);
  
    browser.waitForAngular(); 
    
    browser.sleep(1000); //Wait for the box to animate closed


	}

	alloyEditor(name,text){

		var editor = element(by.css("textarea[name="+name+"] + div.cke_textarea_inline"));

    DomHelper.canInteractCheck(editor);

		editor.sendKeys(text);
	}

	submit(){
    DomHelper.clickBetter(this.submitButton);
	}


	static toggleOn(field){

		field.getAttribute('aria-checked').then(function(attribute){
			if(attribute === "false"){
				field.click();
			}

		});

	}


	static toggleOff(field){
		field.getAttribute('aria-checked').then(function(attribute){
			if(attribute === "true"){
				field.click();
			}

		});
	}

}


module.exports = FormHelper;
