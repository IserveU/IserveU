var LoginPage = require('../../shared/pages/LoginPage');
var FormHelper = require('../../shared/helpers/FormHelper');
var NavigationHelper = require('../../shared/helpers/NavigationHelper');
var faker = require('faker');
var randomstring = require("randomstring");

describe('authenication.verified_citizen page interactions for verified users with the citizen role', function() {

	var page = new LoginPage();
  var EC = protractor.ExpectedConditions;

	beforeEach(function(){
		page.get();

	});

  	it('Should be able to login as citizen', function() {

		expect(browser.isElementPresent(page.getMessage())).toBe(false);

 
  		page.loginWithEmailPassword("citizen@iserveu.ca","abcd1234");
  		

      	browser.wait(EC.urlContains('home'), 5000,"url did not match");

  	});



});
