var LoginPage = require('../../shared/pages/LoginPage');
var FormHelper = require('../../shared/helpers/FormHelper');
var NavigationHelper = require('../../shared/helpers/NavigationHelper');
var faker = require('faker');
var randomstring = require("randomstring");

describe('authenication.unverified_user page interactions for an unverified user with no roles', function() {

	var page = new LoginPage();
	var EC = protractor.ExpectedConditions;

	beforeEach(function(){
		page.get();

	});


  	it('Should be able to login as unverified user', function() {

		expect(browser.isElementPresent(page.getMessage())).toBe(false);

  		page.loginWithEmailPassword("user@iserveu.ca","abcd1234");

      	browser.wait(EC.urlContains('home'), 5000,"url did not match");

  	});


});
