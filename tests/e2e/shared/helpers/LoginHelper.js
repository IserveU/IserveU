let LoginPage 	= require('../pages/LoginPage');
let Setting = require('../pages/setting/Settings');

class LoginHelper {

	constructor(email,password){
		this.email = email;
		this.password = password;

		this.loginPage = new LoginPage();

		this.logoutButton	= element();
	
	}

	login(email){
		if(email){
			this.email = email;
		}

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
		let setting = new Setting();
  		setting.openSettingsSection('logout');
		
	}

}


module.exports = LoginHelper;