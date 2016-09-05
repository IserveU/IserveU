var UserLoginPage = function() {

	this.testEmail = Math.random().toString(36).substring(7).concat('@iserveu.ca');


  	this.loginEmail = element(by.model('login.service.credentials.email'));
    this.loginPassword = element(by.model('login.service.credentials.password'));
    this.loginButton = element(by.className('login__button'));

    this.createButton = element(by.className('create__button'));
    this.registerForm = element(by.name('registerform'));


	this.login = function(credentials) {
	    this.loginEmail.sendKeys(credentials.email);
	    this.loginPassword.sendKeys(credentials.password);
	    this.loginButton.click();
	};
};

module.exports = UserLoginPage;