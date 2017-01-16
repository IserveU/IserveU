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

	static scrollIntoView(element) {
		browser.executeScript('arguments[0].scrollIntoView()', element.getWebElement());

 	//	arguments[0].scrollIntoView();
	}

	static clickBetter(element){
		var EC = protractor.ExpectedConditions;

		browser.wait(EC.presenceOf(element), 2000, "The element is not in the DOM");

		browser.wait(EC.visibilityOf(element), 2000, "The element is in the DOM but not visible");

		browser.wait(EC.elementToBeClickable(element), 2000, "The element is in the DOM and visible but not clickalble");

		this.scrollIntoView(element);
		element.getText().then(function(name){
			console.log(name+' being clicked');
		});

		element.click();

		element.getText().then(function(name){
			console.log(name+' clicked');
		});
	}


}


module.exports = DomHelper;
