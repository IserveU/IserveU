let LoginPage = require('../../shared/pages/LoginPage');
let FormHelper = require('../../shared/helpers/FormHelper');
let faker = require('faker');
let randomstring = require("randomstring");
let ConsoleHelper = require('../../shared/helpers/ConsoleHelper');

describe('authenication.verified_citizen page interactions for verified users with the citizen role', function() {

	let page = new LoginPage();
  let EC = protractor.ExpectedConditions;

	beforeEach(function(){
		page.get();

	});

  	it('Should be able to login as citizen', function() {

		expect(browser.isElementPresent(page.getMessage())).toBe(false);

 
    		page.loginWithEmailPassword("citizen@iserveu.ca","abcd1234");
  		

      	browser.wait(EC.urlContains('home'), 5000,"url did not match");

  	});


    afterEach(function(){
  
        ConsoleHelper.printErrors();
    });

});
