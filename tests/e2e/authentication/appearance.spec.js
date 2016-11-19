let LoginPage = require('../shared/pages/LoginPage');


describe('authenication.appearance iserveu first landing on login page as new user', function() {

	let page = new LoginPage();

	beforeEach(function(){
		page.get();

	});

  	it('Should see correct logo and title', function() {

	    expect(page.getTitle()).toBe("IserveU - eDemocracy");
	    expect(page.getLogo('src')).toContain("/api/page/1/file/logo-png/resize/1920");
   	    expect(page.getFavicon('href')).toContain("/api/page/1/file/symbol-png/resize/100");

  	});


});
