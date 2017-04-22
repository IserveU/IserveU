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
  
  static canInteractCheck(element){
    var EC = protractor.ExpectedConditions;
    
		browser.wait(EC.presenceOf(element), 5000, "The element is not in the DOM");
    
    DomHelper.scrollIntoView(element); //Makes sure it's scrolled into view for next check

		browser.wait(EC.visibilityOf(element), 5000, "The element is in the DOM but not visible");

		browser.wait(EC.elementToBeClickable(element), 5000, "The element is in the DOM and visible but not clickable");

    
  }

	static clickBetter(element){

		if(!element){
			console.log("The element has not been set");
		}

		let me = this;

		element.getText().then(function(buttonText){
			   if(buttonText === undefined){
           buttonText = "[Textless Button]";
         }
         me.buttonText = buttonText;
		});
    
    this.canInteractCheck(element);

		console.log(this.buttonText+' being clicked');

		element.click();

		console.log(this.buttonText+' clicked');

	}


}


module.exports = DomHelper;
