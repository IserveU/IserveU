
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
	selectBox(model,text){
		//Open the element
 		element(by.model(model)).click();

 		//Click the text
 		element(by.cssContainingText('md-option', text)).click();
	}

	submit(){
		this.submitButton.click();
	}

}


module.exports = FormHelper;
