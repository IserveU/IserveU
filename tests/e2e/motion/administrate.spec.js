let AdministrateMotionPage = require('../shared/pages/Motion/AdministrateMotionPage');
let LoginHelper = require('../shared/helpers/LoginHelper');
let SidebarSection = require('../shared/pages/Motion/SidebarSection');
let FormHelper = require('../shared/helpers/FormHelper');
let faker = require('faker');
let DomHelper = require('../shared/helpers/DomHelper');
let ConsoleHelper = require('../shared/helpers/ConsoleHelper');

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

      browser.sleep(1000); //This next line fails all the time.
   		formHelper.selectBox('form.motion.department.id',"Unknown");
      
      browser.sleep(1000); //This next line fails all the time. I think because the box above it doesn't finish closing
   		formHelper.selectBox('form.motion.status',"Draft");
      
      browser.sleep(1000); //This next line fails all the time. I think because the box above it doesn't finish closing
 		  formHelper.submit();

		//This failed during some query updates for the first time on 2017/01/06
		  browser.wait(EC.urlContains("/motion/"),10000,"Motion did not redirect");

  	});

    afterEach(function(){
    
        ConsoleHelper.printErrors();
    });


});
