let MotionPage = require('../shared/pages/Motion/ShowMotionPage');
let SidebarSection = require('../shared/pages/Motion/SidebarSection');
let LoginHelper = require('../shared/helpers/LoginHelper');


describe('motion.appearance making sure that a motion looks correct', function() {

	let motion = new MotionPage();
	let login = new LoginHelper();


	beforeEach(function(){
		login.login();
		motion.get();
	});


  	it('Can see all parts of motion', function() {
  		
		var EC = protractor.ExpectedConditions;

		expect(motion.getTitle('text')).toBe("A Published Motion");
		expect(motion.getText('text')).toContain("Content of the published motion");

		browser.wait(EC.elementToBeClickable(motion.getFile('An Attached PDF'), 5000));

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

		browser.wait(EC.not(EC.urlIs(currentUrl)),3000);


  	});


});
