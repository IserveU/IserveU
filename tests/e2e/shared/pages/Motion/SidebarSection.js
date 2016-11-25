let DomHelper = require('../../helpers/DomHelper');
let ShowMotionPage = require('../../pages/Motion/ShowMotionPage');


class SidebarSection extends ShowMotionPage{

	constructor(){
		super();
		
		/* Button lookups */
		this.sidebar 				= 	element(by.css('motion-sidebar'));
		this.sidebarMotions 		= 	element.all(by.css('motion-sidebar md-list-item'));


	}


	clickRandomMotion(){
		this.getRandomMotion().then(function(val){
			val.click();
		});

	}

	getRandomMotion(){

		//Motions that aren't already open and slightly greyed
		let nonActiveMotions = this.sidebarMotions.filter(function (option) {
		    return option.getAttribute('class').then(function (attribute) {
		       	return !(attribute.includes("active"));
		    });
		});

		
		var indexes = nonActiveMotions.map(function (option, index) {
		    return index;
		});

		return indexes.then(function (indexes) {
		    var randomIndex = indexes[Math.floor(Math.random()*indexes.length)];

		   return nonActiveMotions.get(randomIndex);

		});


	}


}



module.exports = SidebarSection;