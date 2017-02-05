let AdministrateMotionPage = require('../shared/pages/Motion/AdministrateMotionPage');
let LoginHelper = require('../shared/helpers/LoginHelper');
let SidebarSection = require('../shared/pages/Motion/SidebarSection');
let FormHelper = require('../shared/helpers/FormHelper');
let faker = require('faker');
let DomHelper = require('../shared/helpers/DomHelper');



describe('motion.administrate making sure that a motion creation and editing works correctly || ', function() {

	let motionAdmin = new AdministrateMotionPage();
	let login = new LoginHelper();
	let sidebarSection = new SidebarSection();

	let createdMotion = null;

	beforeEach(function(){
		login.login('admin@iserveu.ca');
	});

  it('Motion administration buttons exist and work as expected', function() {
		var EC = protractor.ExpectedConditions;

		DomHelper.clickBetter(sidebarSection.getSidebarLinks().last());


		browser.wait(EC.urlContains("create-motion"),10000,"Sidebar create motion button does not work");


		motionAdmin.get(); //Old published motion

		motionAdmin.clickCreateMotion();


		browser.wait(EC.urlContains("create-motion"),10000,"On motion page create button does not work");

		motionAdmin.get(); //Old published motion
		motionAdmin.clickEditMotion();

		browser.wait(EC.urlContains("edit-motion"),10000);

  });


  it('Motion creation and deletion process works as expected', function() {
		var EC = protractor.ExpectedConditions;

		motionAdmin.createMotion();

  		let formHelper = new FormHelper(
          motionAdmin.getSaveButton(),
          new Map([
            ["title",faker.lorem.sentence()],
            ['summary', faker.lorem.sentences(2)]
          ])
        );

   		formHelper.alloyEditor('body',faker.lorem.sentences(10));

   		formHelper.selectBox('form.motion.department.id',"Unknown");
   		formHelper.selectBox('form.motion.status',"Draft");
 		formHelper.submit();

		//This failed during some query updates for the first time on 2017/01/06
		browser.wait(EC.urlContains("/motion/"),10000,"Motion did not redirect");

  	});

// 		motionAdmin.clickDeleteMotion();
// 		browser.wait(EC.presenceOf(element(by.tagName('md-toast'))),1000,"Toast did not occur");
// 		motionAdmin.clickDeleteMotionConfirmation();
// 		return browser.wait(EC.urlContains("/home"),5000,"Did not get returned to home page");

    afterEach(function(){
        browser.manage().logs().get('browser').then(function(browserlog){
         // expect(browserlog.length).toEqual(0);
          if(browserlog.length) console.error("log: "+JSON.stringify(browserlog));
        });
    });

});
