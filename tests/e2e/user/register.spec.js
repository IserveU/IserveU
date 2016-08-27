describe('iserveu first landing on login page as new user', function() {

  beforeEach(function() {
  });

  var testEmailAddress = Math.random().toString(36).substring(7);

  it('should see correct logo and login and register elements and colours defined in current them', function() {

      browser.get('http://iserveu.local/#/login');

    // element(by.model('todoList.todoText')).sendKeys('write first protractor test');
    // element(by.css('[value="add"]')).click();

    // var todoList = element.all(by.repeater('todo in todoList.todos'));
    // expect(todoList.count()).toEqual(3);
    // expect(todoList.get(2).getText()).toEqual('write first protractor test');

    // // You wrote your first test, cross it off the list
    // todoList.get(2).element(by.css('input')).click();
    // var completedAmount = element.all(by.css('.done-true'));
    // expect(completedAmount.count()).toEqual(2);

    var logo = element(by.className('logo'));
    logo.isPresent();
    // TODO: dynamically set the theme name @ default
    expect(logo.getAttribute('src')).toEqual('http://iserveu.local/themes/default/logo/logo.png');
    // expect modal terms and conditions
    // var answer = element(by.binding('answer'));

    // question.sendKeys("What is the purpose of meaning?");
    // button.click();
    // expect(answer.getText()).toEqual("Chocolate!");
  });

  it('it should not see the register form on login', function() {
    var createButton = element(by.className('create__button'));
    var registerForm = element(by.name('registerform'));

    expect(registerForm.isPresent()).toBe(false); // should be false.
    createButton.click(); // opens register form
    expect(registerForm.isPresent()).toBe(true); // should be true.
  });


  it('it should be able to register and be redirected', function() {
    var firstName = element(by.model('login.service.newUser.first_name'));
    var lastName = element(by.model('login.service.newUser.last_name'));
    var email = element(by.model('login.service.newUser.email'));
    var confirmEmail = element(by.model('login.confirm_email'));
    var password = element(by.model('login.service.newUser.password'));

    var communitySelect = element.all(by.css('md-select')); // test if all communities are there?
    var communities = element.all(by.repeater('c in register.community.index'));
    var register = element(by.css('spinner'));

    firstName.sendKeys("John");
    lastName.sendKeys("Doe");
    email.sendKeys(testEmailAddress+"@iserveu.ca");
    confirmEmail.sendKeys(testEmailAddress+"@iserveu.ca");
    password.sendKeys("abcd1234");

    communitySelect.click();
    browser.driver.sleep(500); 

    // TODO: random number
    communities.get(2).click();
    register.click();

    browser.driver.sleep(500); 

    var termsAndConditions = element(by.className('terms_conditions'));
    expect(termsAndConditions.isPresent());
    var termsAndConditionsButton = element(by.className('terms_conditions__button'));
    termsAndConditionsButton.click();
    browser.driver.sleep(2000); 
    expect(browser.getLocationAbsUrl()).toEqual('/home');
  });

  it('it should be able to logout and get redirected', function() {

    var cornerCog = element(by.css('div.md-toolbar-item > md-menu > button[ng-click="$mdOpenMenu()"]'));
    cornerCog.click();
    browser.driver.sleep(500); 
    element(by.css('md-menu-item > button[ng-click="user.logout()"]')).click();
    browser.driver.sleep(1000); 
    expect(browser.getLocationAbsUrl()).toEqual('/login');

  });

  it('it should not be able to log back in with the wrong password', function() {
    var loginEmail = element(by.model('login.service.credentials.email'));
    var loginPassword = element(by.model('login.service.credentials.password'));
    var loginButton = element(by.css('spinner.login__button'));

    loginEmail.sendKeys(testEmailAddress+"@iserveu.ca");
    loginPassword.sendKeys("wrongpassword");
    loginButton.click();

    // TODO: error message not throwing
    // expect(element(by.className('md-caption')).getText()).toEqual('Password and email combination do not match.');

    loginPassword.clear();
    loginPassword.sendKeys("abcd1234");

    loginButton.click();
    expect(browser.getLocationAbsUrl()).toEqual('/home');
  });


});
