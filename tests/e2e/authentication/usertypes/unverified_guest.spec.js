let LoginPage = require('../../shared/pages/LoginPage');
let FormHelper = require('../../shared/helpers/FormHelper');
let faker = require('faker');
let randomstring = require("randomstring");

describe('authenication.guest page interactions for a guest users (no account)', function() {

	let page = new LoginPage();
  let EC = protractor.ExpectedConditions;

	beforeEach(function(){
		page.get();

	});

  	it('Should see correct error message after login attempt', function() {

		  expect(browser.isElementPresent(page.getMessage())).toBe(false);

  		let email 			= faker.internet.email();
      let password 		= faker.internet.password();

  		page.loginWithEmailPassword(email,password);

		  expect(page.getMessage().getText()).toBe("Password and email combination do not match.");

  	});


  	it('Should see forget password reminder after login attempt', function() {

		  expect(browser.isElementPresent(page.getForgotPassword())).toBe(false);

  		let email 		= faker.internet.email();
      let password 	= faker.internet.password();

  		page.loginWithEmailPassword(email,password);

		  expect(page.getForgotPassword().getText()).toBe("Password and email combination do not match.");

  	});


  	it('Should be able to signup for site', function() {
        let email     = faker.internet.email();
        page.clickCreateButton();

  		  let formHelper = new FormHelper(
          page.getCreateButton(),
          new Map([
            ["newemail",email],
            ['confirmemail', email],
            ['firstname', faker.name.firstName()],
            ['lastname', faker.name.lastName()],
            ['newpassword', faker.internet.password()]
          ])
        );

   		  formHelper.selectBox('login.service.newUser.community_id',"Yellowknife");

     		formHelper.submit();

    		browser.driver.sleep(500);

     		page.clickIAgreeButton();

     		browser.wait(EC.urlContains('home'), 5000,"url did not match");

  	});

});
