var FormHelper = function() {


	this.fillFields = function(content){
		for (var [key, value] of content) {
			element(by.name(key)).sendKeys(value);
		}
	}

	/**
	 * Handles the md-selects and their unusual display format
	 * @param  {String} name [description]
	 * @param  {String} text [description]
	 * @return {[type]}      [description]
	 */
	this.selectBox = function (model,text){
		//Open the element
 		element(by.model(model)).click();

 		//Click the text
 		element(by.cssContainingText('md-option', text)).click();
	}

}


module.exports = FormHelper;