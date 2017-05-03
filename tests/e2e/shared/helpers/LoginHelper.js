let LoginPage 	= require('../pages/LoginPage');
let Setting = require('../pages/setting/Settings');
let faker = require('faker');
let FormHelper = require('../helpers/FormHelper');
let DomHelper = require('../helpers/DomHelper');

class LoginHelper {


	constructor(email,password){
		this.email = email;
		this.password = password;

		this.loginPage = new LoginPage();


	}

	login(email){

    var EC = protractor.ExpectedConditions;

    this.loginPage.get();

    browser.wait(EC.urlContains('login'), 5000,"Unable to open the login page");
  
    browser.sleep(1000);
    
    var vm = this;
    
    var button = element(by.css('md-dialog-content button.terms_conditions__button'));
    
    button.isPresent().then(function(result) {
        if ( result ) {
              DomHelper.clickBetter(button);
        } else {
          console.log('no terms button');
        }
    });
    
		if(email){
			this.email = email;
		}

		if(!this.email){
			this.email = "admin@iserveu.ca";
		}

		if(!this.password){
			this.password = "abcd1234";
		}
		
		this.loginPage.loginWithEmailPassword(this.email, this.password);

		return browser.sleep(1000); // If this times out just use browser.waitForAngular();
    
	}

  create(email){
    let EC = protractor.ExpectedConditions;

    this.loginPage.get();
    
    browser.wait(EC.urlContains('#/login'), 5000,"Unable to raise the login page");

    if(email === undefined){
      email     = faker.internet.email();
    }

    this.loginPage.clickCreateButton();

    let details = new Map([
      ["newemail",email],
      ['confirmemail', email],
      ['firstname', faker.name.firstName()],
      ['lastname', faker.name.lastName()],
      ['newpassword', 'abcd1234']
    ]);

    let formHelper = new FormHelper(
      this.loginPage.getCreateButton(),
      details
    );

    formHelper.selectBox('register.values.community_id',"Yellowknife");
    formHelper.submit();
    
    browser.driver.sleep(1000); //Have to wait for this to be placed on the DOM
    this.loginPage.clickIAgreeButton();

    browser.wait(EC.urlContains('home'), 12000,"Account createion stalled out/home page did not appear");
    
    return details;
  }

	logout(){
    let EC = protractor.ExpectedConditions;

		let setting = new Setting();
    
    setting.openCogMenu();
  
    setting.cogMenu.isPresent().then(function(result) {
        if ( result ) {
          setting.cogMenuButtons['logout'].isDisplayed().then(function(displayed){

            if(displayed){
                DomHelper.clickBetter(setting.cogMenuButtons['logout']);
            }
            browser.wait(EC.urlContains('#/home'), 5000,"Did not redirect to home page");
          });
        } else {
          console.log('no terms button');
        }
    });


	}

}


module.exports = LoginHelper;