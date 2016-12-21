
class FormHelper {

	constructor(submitButton,fillFields) {
		this.submitButton = submitButton;
		this.fillFields(fillFields);
	}


	fillFields(content){
		for (let [key, value] of content) {
			element(by.name(key)).sendKeys(value);
		}
	}

	/**
	 * Handles the md-selects and their unusual display format
	 * @param  {String} name [description]
	 * @param  {String} text [description]
	 * @return {[type]}      [description]
	 */

	selectBox(model, text){
		
 		element(by.model(model)).click();
	    browser.waitForAngular(); 

	    let el = element(by.cssContainingText('.md-select-menu-container.md-active md-option > div.md-text', text));
	    if (!(el.isPresent())) {
	        throw Error('Not clickable');
	    }
	    el.click();
	    browser.waitForAngular(); 

	}

	alloyEditor(name,text){
		var EC = protractor.ExpectedConditions;

		var editor = element(by.css("textarea[name="+name+"] + div.cke_textarea_inline"));

		browser.wait(EC.presenceOf(editor), 5000,"Unable to find the cke editor instance");

		editor.sendKeys(text);
	}

	submit(){
		this.submitButton.click();
	}


	static toggleOn(field){

		field.getAttribute('aria-checked').then(function(attribute){
			if(attribute == "false"){
				field.click();
			}

		});

	}


	static toggleOff(field){
		field.getAttribute('aria-checked').then(function(attribute){
			if(attribute == "true"){
				field.click();
			}

		});
	}

}


module.exports = FormHelper;
