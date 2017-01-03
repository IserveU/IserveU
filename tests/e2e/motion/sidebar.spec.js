let MotionPage = require('../shared/pages/Motion/ShowMotionPage');
let SidebarSection = require('../shared/pages/Motion/SidebarSection');
let LoginHelper = require('../shared/helpers/LoginHelper');
let faker = require('faker');

describe('motion.sidebar making sure that a motion sidebar works ||', function() {

	let motion = new MotionPage();
	let login = new LoginHelper();
	let sidebar = new SidebarSection();

	beforeEach(function(){

		login.login();

	});


  	it('Can search by text ', function() {

		var EC = protractor.ExpectedConditions;
  		let word = faker.lorem.words(1);

		sidebar.textSearch(word);

		sidebar.clickRandomMotion();
		expect(motion.containsText(word)).toEqual(true);

		sidebar.clickRandomMotion();
		expect(motion.containsText(word)).toEqual(true);

		sidebar.clickRandomMotion();
		expect(motion.containsText(word)).toEqual(true);

  	});


  	
    afterEach(function(){
        browser.manage().logs().get('browser').then(function(browserlog){
         // expect(browserlog.length).toEqual(0);
      //    if(browserlog.length) console.error("log: "+JSON.stringify(browserlog));
        });
    });

});
