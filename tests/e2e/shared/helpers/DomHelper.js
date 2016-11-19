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

}


module.exports = DomHelper;