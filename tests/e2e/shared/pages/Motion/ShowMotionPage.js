let DomHelper = require('../../helpers/DomHelper');

class ShowMotionPage{

	constructor(){
		/* Motion Details */
		this.motionTitle 			= element(by.css('h1.motion_header__title'));
		this.motionSummary 			= element(by.css('p.motion_header__summary'));
		this.motionText 			= element(by.binding('motion.text'));
		this.motionFiles 			= element.all(by.repeater('file in motion.motionFiles'))

		/* Create Comment UI */
		this.createCommentHeader	= element(by.css('user-comment-create header'));
		this.submitCommentButton 	= element(by.css('user-comment-create button[type=submit]'));
		this.closeCommentButton 	= element(by.css('user-comment-create button[type=button]'));

		// Motion Model Icons (Vote is not here)
		this.departmentIcon			= element(by.id('department_icon')); 
		this.closingIcon 			= element(by.css('#closing_tile md-icon'));

	}


	get(slug){
		if(!slug){
			slug = "a-published-motion";			
		}

		return browser.get('/#/motion/'+slug);
		
	}

	getTitle(attr){
		browser.sleep(2000);

		return DomHelper.extractAttribute(this.motionTitle,attr);
	}

	getText(attr){
		return DomHelper.extractAttribute(this.motionText,attr);
	}

	getSummary(attr){
		return DomHelper.extractAttribute(this.motionSummary,attr);
	}

	getDepartmentIcon(attr){
		return DomHelper.extractAttribute(this.departmentIcon,attr);
	}

	getClosingIcon(attr){
		return DomHelper.extractAttribute(this.closingIcon,attr);
	}


	getFile(title){
		if(!title){
			return this.motionFiles;
		}

		return this.motionFiles.$$('a').filter(function(elem, index) {
		  	return elem.getText().then(function(text) {
		    	return text === title;
		  	});
		}).first();

	}

	containsText(text){
		text = text.toLowerCase();
		let vm = this;
		let deferred = protractor.promise.defer();

		let contained = false;
		this.getTitle("text").then(function(content){
			if(content.toLowerCase().includes(text)){
				contained = true;
			}
			//console.log(contained + "(" + text + ")" + content);

			vm.getSummary("text").then(function(content){
				if(content.toLowerCase().includes(text)){
					contained = true;
				}
			//	console.log(contained + "(" + text + ")" + content);

				vm.getText("text").then(function(content){
					if(content.toLowerCase().includes(text)){
						contained = true;
					}
				//	console.log(contained + "(" + text + ")" + content);

					deferred.fulfill(contained);
				});
			});
		});

		
		return deferred.promise;
	}
}



module.exports = ShowMotionPage;