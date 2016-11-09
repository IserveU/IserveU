var LoginPage = require('../../shared/pages/LoginPage');
var FormHelper = require('../../shared/helpers/FormHelper');
var NavigationHelper = require('../../shared/helpers/NavigationHelper');
var faker = require('faker');
var randomstring = require("randomstring");

describe('authenication.guest page interactions for a guest users (no account)', function() {

	var page = new LoginPage();

	beforeEach(function(){
		page.get();

	});


  	it('Should be able to login as unverified user', function() {

		expect(browser.isElementPresent(page.getMessage())).toBe(false);

  		page.loginWithEmailPassword("user@iserveu.ca","abcd1234 ");

  		var EC = protractor.ExpectedConditions;
      	browser.wait(EC.urlContains('home'), 5000,"url did not match");

  	});


});
