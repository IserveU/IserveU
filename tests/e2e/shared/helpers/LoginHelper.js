let LoginPage = require('../pages/LoginPage');

class LoginHelper {

	constructor(email,password){
		this.email = email;
		this.password = password;

		this.loginPage = new LoginPage();
	}

	login(){
		if(!this.email){
			this.email = "admin@iserveu.ca";
		}

		if(!this.password){
			this.password = "abcd1234";
		}
		
		this.loginPage.get();
		this.loginPage.loginWithEmailPassword(this.email, this.password);

		browser.sleep(1000); // If this times out just use browser.waitForAngular();

	}


	logout(){
		
	}

}


module.exports = LoginHelper;