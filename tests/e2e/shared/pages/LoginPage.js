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
		this.loginButton.click();
	}

	getCreateButton(){
		return this.createButton;
	}

	clickCreateButton(){
		this.createButton.click();
	}

	clickIAgreeButton(){
		this.agreeButton.click();
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