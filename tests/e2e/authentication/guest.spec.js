var LoginPage = require('../shared/pages/LoginPage');
var FormHelper = require('../shared/helpers/FormHelper');
var NavigationHelper = require('../shared/helpers/NavigationHelper');
var faker = require('faker');
var randomstring = require("randomstring");

describe('authenication.guest page interactions for a guest users (no account)', function() {

	var page = new LoginPage();

	beforeEach(function(){
		page.get();

	});

  	it('Should see correct error message after login attempt', function() {

		expect(browser.isElementPresent(page.getMessage())).toBe(false);

  		var email 			= faker.internet.email();
 		var password 		= faker.internet.password();

  		page.loginWithEmailPassword(email,password);

		expect(page.getMessage().getText()).toBe("Password and email combination do not match.");

  	});


  	it('Should see forget password reminder after login attempt', function() {

		expect(browser.isElementPresent(page.getForgotPassword())).toBe(false);

  		var email 		= faker.internet.email();
 		var password 	= faker.internet.password();

  		page.loginWithEmailPassword(email,password);

		expect(page.getForgotPassword().getText()).toBe("Password and email combination do not match.");

  	});


  	it('Should be able to signup for site', function() {
		var formHelper = new FormHelper();
		var navigationHelper = new FormHelper();	

  		page.clickCreateButton();

  		var email 		= faker.internet.email();

  		var formFields = new Map([
  			["newemail",email],
  			['confirmemail', email],
  			['firstname', faker.name.firstName()],
  			['lastname', faker.name.lastName()],
  			['newpassword', faker.internet.password()]
  		]);


 		formHelper.fillFields(formFields);

 		formHelper.selectBox('login.service.newUser.community_id',"Yellowknife");

 		page.clickCreateButton();
		browser.driver.sleep(500);

 		page.clickIAgreeButton();

		var EC = protractor.ExpectedConditions;
 		browser.wait(EC.urlContains('home'), 5000,"url did not match");

  	});

});
