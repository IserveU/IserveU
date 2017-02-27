let LoginHelper 		= require('../../shared/helpers/LoginHelper');
let AdministrationPage 	= require('../../shared/pages/setting/AdministrationPage');
let MotionPage  = require('../../shared/pages/Motion/ShowMotionPage');
let ConsoleHelper = require('../../shared/helpers/ConsoleHelper');
let FormHelper			= require('../../shared/helpers/FormHelper');

describe('settings.administration.authentication settings for authentication || ', function() {

	let login 				=	new LoginHelper();
	let administrationPage 	= new AdministrationPage();

	beforeEach(function(){
		login.login('admin@iserveu.ca');

	});

  	xit('login required toggle should block access to motions', function() {
      let motion = new MotionPage();
      var EC = protractor.ExpectedConditions;

      //Navigate to setting and ensure it is turned on
  		administrationPage.openSettingsSection('site-administration');
  		administrationPage.openTab('System');
  		administrationPage.openSection('Security');
  		FormHelper.toggleOn(administrationPage.settingsInputs.authentication.required);
  		login.logout();

      //Get a motion and check you're redirected to login page
      motion.get();
      browser.wait(EC.urlContains("login"),10000,"The site directed people back to the login page");

      //Login and turn setting off
      login.login('admin@iserveu.ca');
      administrationPage.openSettingsSection('site-administration');
      administrationPage.openTab('System');
      administrationPage.openSection('Security');
      FormHelper.toggleOff(administrationPage.settingsInputs.authentication.required);
      login.logout();

      //Should be able to see the published motion
      motion.get();
      browser.waitForAngular();
      browser.wait(EC.urlContains("a-published-motion"),10000,"The site lets people look at published motions now");

  	});
    
    afterEach(function(){
        ConsoleHelper.printErrors();
    });

});
