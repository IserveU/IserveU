class DomHelper {

	constructor() {

	}

	static extractAttribute(element, attr){
		if(attr=="text"){
			return element.getText();
		}

		if(attr){
			return element.getAttribute(attr);
		}

		return element;
	}

	scrollIntoView(element) {
 		arguments[0].scrollIntoView();
	}

	static clickBetter(element){
		var EC = protractor.ExpectedConditions;

		browser.wait(EC.presenceOf(element), 2000, "The element is not in the DOM");

		browser.wait(EC.visibilityOf(element), 2000, "The element is in the DOM but not visible");

		browser.wait(EC.elementToBeClickable(element), 2000, "The element is in the DOM and visible but not clickalble");

		element.click();

		element.getText().then(function(name){
			console.log(name+' clicked');
		});
	}


}


module.exports = DomHelper;
