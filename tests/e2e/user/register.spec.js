var UserLoginPage = require('../helpers/user-login.page');
var UserRegisterPage = require('../helpers/user-register.page');
var Menu = require('../helpers/menu.page');
var testEmailAddress = Math.random().toString(36).substring(7).concat('@iserveu.ca');

xdescribe('iserveu first landing on login page as new user', function() {

  it('should see correct logo and login and register elements and colours defined in current them', function() {

    browser.get('#/login');

    var logo = element(by.className('logo'));
    expect(logo.isPresent());
    expect(logo.getAttribute('src')).toContain('themes/default/logo/logo.png');

  });


  it('it should not see the register form on login', function() {
    var login = new UserLoginPage();
    expect(login.registerForm.isPresent()).toBe(false);
    login.createButton.click(); // opens register form
    expect(login.registerForm.isPresent()).toBe(true);
  });



  it('it should be able to register and be redirected', function() {
    var register = new UserRegisterPage();
    var login = new UserLoginPage();


    login.createButton.click(); // opens register form

    register.fillRegisterForm({
      email: testEmailAddress,
      password: 'abcd1234'
    });

    register.registerButton.click();
    browser.driver.sleep(500);

    expect(register.termsAndConditions.isPresent());
    register.termsAndConditionsButton.click();
    browser.driver.sleep(2000);
    expect(browser.getLocationAbsUrl()).toEqual('/home');

  });

  // it('it should see correct welcome page', function() {


  // });


  it('it should be able to logout and get redirected', function() {

    var menu = new Menu();

    var injector = angular.element(document.body).injector()
    var service = injector.get('infinite-scroll');
    service.stop();

    menu.dropdown.click();
    browser.driver.sleep(500);
    menu.logoutButton.click();
    browser.driver.sleep(1000);
    expect(browser.getLocationAbsUrl()).toEqual('/login');

  });


  it('it should not be able to log back in with the wrong password', function() {

    // var login = new UserLoginPage();

    // login.login({
    //   email: testEmailAddress,
    //   password: 'wrongpassword'
    // });

    // // TODO: error message not throwing
    // // expect(element(by.className('md-caption')).getText()).toEqual('Password and email combination do not match.');

    // login.loginPassword.clear();
    // login.loginPassword.sendKeys("abcd1234");
    // login.loginButton.click();

    // expect(browser.getLocationAbsUrl()).toEqual('/home');

  });

});
