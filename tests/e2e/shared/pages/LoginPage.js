let DomHelper = require('../helpers/DomHelper');

class LoginPage{

	constructor() {
		this.loginButton 			= element(by.buttonText('Login'));
		this.createButton 			= element(by.buttonText('Create'));
		this.agreeButton 			= element(by.buttonText('I Agree'));

		this.emailInput 			= element(by.name('email'));
		this.passwordInput 			= element(by.name('password'));
		this.logo 					= element(by.css('img.logo'));
		this.favicon 				= element(by.css('link[type="image/png"]'));

		this.message				= element(by.css('div.md-input-messages-animation .md-caption'));	
		this.forgotPassword 		= element(by.css('[ng-click="login.sendResetPassword()"]'));
		this.termsAndConditions 	= element(by.css('md-dialog.terms-and-conditions'));

	}


	/* Element lookups */

	getLoginButton(){
		return this.loginButton;
	}

	clickLoginButton(){
		DomHelper.clickBetter(this.loginButton);
	}

	getCreateButton(){
		return this.createButton;
	}

	clickCreateButton(){
		DomHelper.clickBetter(this.createButton);
	}

	clickIAgreeButton(){
		DomHelper.clickBetter(this.agreeButton);
	}

	getTitle(){
		return browser.getTitle();
	}

	getLogo(attr){
		return DomHelper.extractAttribute(this.logo,attr);
	}

	getFavicon(attr){
		return DomHelper.extractAttribute(this.favicon,attr);
	}

	get(){
		browser.get('#/login');
    browser.sleep(1000);

    var button = element(by.css('md-dialog-content button.terms_conditions__button'));

    button.isPresent().then(function(result) {
        if ( result ) {
            button.click();
        } else {
          console.log('no terms button');
        }
    });
	}

	loginWithEmailPassword(email,password){
		browser.waitForAngular();
		this.emailInput.sendKeys(email);
	    this.passwordInput.sendKeys(password);
	    this.clickLoginButton();
   		browser.waitForAngular();
	}

	getMessage(){
		return this.message;
	}

	getForgotPassword(){
		return this.message;
	}


}

module.exports = LoginPage;