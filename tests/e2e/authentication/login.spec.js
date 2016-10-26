describe('iserveu first landing on login page as new user', function() {

  it('Should see correct logo and login and register elements and colours defined in current theme', function() {

    browser.get('#/login');

    var logo = element(by.className('logo'));
    expect(logo.isPresent());
    expect(logo.getAttribute('src')).toEqual( browser.baseUrl + 'themes/default/logo/logo.png');

  });


});
