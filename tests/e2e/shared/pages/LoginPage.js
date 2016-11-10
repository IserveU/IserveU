var LoginPage = function LoginPage(){

	/* Element lookups */
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



	this.clickLoginButton = function(){
		this.loginButton.click();
	}

	this.clickCreateButton = function(){
		this.createButton.click();
	}

	this.clickIAgreeButton = function(){
		this.agreeButton.click();
	}

	this.getTitle = function(){
		return browser.getTitle();
	}

	this.getLogo = function(attr){
		
		if(attr){
			return this.logo.getAttribute(attr);
		}
		return this.logo;
	}

	this.getFavicon = function(attr){
		if(attr){
			return this.favicon.getAttribute(attr);
		}
		return this.favicon;
	}

	this.get = function(){
		browser.get('#/login');
	}

	this.loginWithEmailPassword = function(email,password){
		this.emailInput.sendKeys(email);
	    this.passwordInput.sendKeys(password);
	    this.clickLoginButton();
	}

	this.getMessage = function(){
		return this.message;
	}

	this.getForgotPassword = function(){
		return this.message;
	}


}

module.exports = LoginPage;