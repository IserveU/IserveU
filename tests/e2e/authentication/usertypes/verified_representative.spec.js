var LoginPage = require('../../shared/pages/LoginPage');
var FormHelper = require('../../shared/helpers/FormHelper');
var NavigationHelper = require('../../shared/helpers/NavigationHelper');
var faker = require('faker');
var randomstring = require("randomstring");

describe('authenication.representative page interactions for a verified user with the representative role', function() {

	var page = new LoginPage();
	var EC = protractor.ExpectedConditions;

	beforeEach(function(){
		page.get();

	});

  	it('Should be able to login as representative', function() {

		expect(browser.isElementPresent(page.getMessage())).toBe(false);

 
  		page.loginWithEmailPassword("representative@iserveu.ca","abcd1234");
  	

      	browser.wait(EC.urlContains('home'), 5000,"url did not match");

  	});



});
