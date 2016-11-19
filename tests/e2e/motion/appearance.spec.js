let MotionPage = require('../shared/pages/MotionPage');


describe('motion.appearance making sure that a motion looks correct', function() {

	let motion = new MotionPage();

	beforeEach(function(){
		motion.get();
	});

  	it('Can see all parts of motion', function() {
		var EC = protractor.ExpectedConditions;

		expect(motion.getTitle('text')).toBe("A Published Motion");
		expect(motion.getText('text')).toContain("Content of the published motion");

		browser.wait(EC.elementToBeClickable(motion.getFile('An Attached PDF'), 5000));

  	});


});
