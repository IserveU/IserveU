var UserRegisterPage = function() {

	this.testEmail = Math.random().toString(36).substring(7).concat('@iserveu.ca');


    this.firstName = element(by.model('login.service.newUser.first_name'));
    this.lastName = element(by.model('login.service.newUser.last_name'));
    this.email = element(by.model('login.service.newUser.email'));
    this.confirmEmail = element(by.model('login.confirm_email'));
    this.password = element(by.model('login.service.newUser.password'));

    this.communitySelect = element.all(by.css('md-select')); // test if all communities are there?
    this.communities = element.all(by.repeater('c in register.community.index'));

    this.registerButton = element(by.css('spinner'));

    this.termsAndConditions = element(by.className('terms_conditions'));
    this.termsAndConditionsButton = element(by.className('terms_conditions__button'));


	this.fillRegisterForm = function(credentials) {
	    this.firstName.sendKeys("John");
	    this.lastName.sendKeys("Doe");
	    this.email.sendKeys(credentials.email);
	    this.confirmEmail.sendKeys(credentials.email);
	    this.password.sendKeys(credentials.password);

	    this.communitySelect.click();
	    // browser.driver.sleep(500); 
	    // TODO: random number
	    this.communities.get(2).click();
	};
};

module.exports = UserRegisterPage;