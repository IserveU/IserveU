let DomHelper = require('../../helpers/DomHelper');
let ShowMotionPage = require('../../pages/Motion/ShowMotionPage');

var scrollIntoView = function (element) {
				//Will need to refactor, but this scrolls the window to the element
  arguments[0].scrollIntoView();
};

class SidebarSection extends ShowMotionPage{

	constructor(){
		super();
		
		/* Button lookups */
		this.sidebar 				= 	element(by.css('motion-sidebar'));
		this.sidebarItems 			= 	element.all(by.css('motion-sidebar md-list-item'));
		this.sidebarLinks 			= 	element.all(by.css('motion-sidebar md-list-item a'));

	}

	clickRandomMotion(){
		browser.waitForAngular();
		this.getRandomMotion().then(function(val){
			//Will need to refactor, but this scrolls the window to the element which 
			//a promise that gets one item from an ElementArrayFinder won't do it seems
			//http://stackoverflow.com/questions/27023768/scroll-down-to-an-element-with-protractor
			browser.executeScript(scrollIntoView,val.getWebElement());
			val.click();
		});
	}

	getRandomMotion(){
		let deferred = protractor.promise.defer();



		//Motions that aren't already open and slightly greyed
		let nonActiveMotions = this.getSidebarItems().filter(function (option) {
		    return option.getAttribute('class').then(function (attribute) {
		       	return !(attribute.includes("active"));
		    });
		});

		
		var indexes = nonActiveMotions.map(function (option, index) {
		    return index;
		});

		indexes.then(function (indexes) {
			let validMotions = indexes.length-1; //The last item is not a motion, its a create more motions button

		    var randomIndex = indexes[Math.floor(Math.random()*validMotions)];

		    deferred.fulfill(nonActiveMotions.get(randomIndex));
		});

		return deferred.promise;

	}

	getSidebarItems(){
	    browser.waitForAngular();
		return this.sidebarItems;
	}

	getSidebarLinks(){
	    browser.waitForAngular();
		return this.sidebarLinks;
	}

}



module.exports = SidebarSection;