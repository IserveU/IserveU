class DomHelper {

	constructor() {
		this.buttonText = "";
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

		if(!element){
			console.log("The element has not been set");
		}

		var EC = protractor.ExpectedConditions;


		let me = this;

		element.getText().then(function(buttonText){
			me.buttonText = buttonText;
		});

		browser.wait(EC.presenceOf(element), 2000, "The element is not in the DOM");

		browser.wait(EC.visibilityOf(element), 2000, "The element is in the DOM but not visible");

		browser.wait(EC.elementToBeClickable(element), 2000, "The element is in the DOM and visible but not clickalble");

		this.scrollIntoView(element);

		console.log(this.buttonText+' being clicked');

		element.click();

		console.log(this.buttonText+' clicked');

	}


}


module.exports = DomHelper;
