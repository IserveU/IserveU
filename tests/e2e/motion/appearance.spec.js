let MotionPage = require('../shared/pages/Motion/ShowMotionPage');
let SidebarSection = require('../shared/pages/Motion/SidebarSection');
let LoginHelper = require('../shared/helpers/LoginHelper');


describe('motion.appearance making sure that a motion looks correct ||', function() {

	let motion = new MotionPage();
	let login = new LoginHelper();


	beforeEach(function(){

		login.login();

		return motion.get(); //Returning a promise makes the beforeEach wait
	});


  	it('Can see all parts of motion', function() {


		var EC = protractor.ExpectedConditions;

		expect(motion.getTitle('text')).toBe("A Published Motion");

		expect(motion.getSummary('text')).toBe("The summary of the published motion");

		expect(motion.getText('text')).toContain("Content of the published motion");
		expect(motion.getDepartmentIcon('md-svg-icon')).toBe("/icons/unknown.svg");

		browser.wait(EC.elementToBeClickable(motion.getFile('An Attached PDF')), 5000,"File not showing up");

  	});


	it('Can naviate between motions rapidly', function() {

		var EC = protractor.ExpectedConditions;

		let sidebar = new SidebarSection();

		sidebar.clickRandomMotion();

		let currentUrl = browser.getCurrentUrl();

		sidebar.clickRandomMotion();

		browser.wait(EC.not(EC.urlIs(currentUrl)),3000);

		currentUrl = browser.getCurrentUrl();

		sidebar.clickRandomMotion();
		var EC = protractor.ExpectedConditions;

		browser.wait(EC.not(EC.urlIs(currentUrl)),3000);


  	});


    afterEach(function(){
        browser.manage().logs().get('browser').then(function(browserlog){
         // expect(browserlog.length).toEqual(0);
          if(browserlog.length) console.error("log: "+JSON.stringify(browserlog));
        });
    });

});
