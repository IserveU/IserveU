let LoginPage = require('../../shared/pages/LoginPage');
let FormHelper = require('../../shared/helpers/FormHelper');
let faker = require('faker');
let randomstring = require("randomstring");
let ConsoleHelper = require('../../shared/helpers/ConsoleHelper');

describe('authenication.unverified_user page interactions for an unverified user with no roles', function() {

	let page = new LoginPage();
	let EC = protractor.ExpectedConditions;

	beforeEach(function(){
		page.get();

	});


  	it('Should be able to login as unverified user', function() {

		expect(browser.isElementPresent(page.getMessage())).toBe(false);

  		page.loginWithEmailPassword("user@iserveu.ca","abcd1234");

      	browser.wait(EC.urlContains('home'), 5000,"url did not match");

  	});

    afterEach(function(){

        ConsoleHelper.printErrors();
    });

});
