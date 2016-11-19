let LoginPage = require('../../shared/pages/LoginPage');
let faker = require('faker');
let randomstring = require("randomstring");

describe('authenication.admin page interactions for a verified user with the admin role', function() {

	let page = new LoginPage();
	let EC = protractor.ExpectedConditions;

	beforeEach(function(){
		page.get();

	});

  	it('Should be able to login as admin', function() {

		expect(browser.isElementPresent(page.getMessage())).toBe(false);

 
  		page.loginWithEmailPassword("admin@iserveu.ca","abcd1234");
  	

      	browser.wait(EC.urlContains('home'), 5000,"url did not match");

  	});



});
