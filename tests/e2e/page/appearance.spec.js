let HomePage = require('../shared/pages/Page/ShowHomePage');
let LoginHelper = require('../shared/helpers/LoginHelper');
let CommentSection = require('../shared/pages/Motion/CommentSection');
let faker = require('faker');
let ConsoleHelper = require('../shared/helpers/ConsoleHelper');
let DomHelper = require('../shared/helpers/DomHelper');
let LoginPage = require('../shared/pages/LoginPage');
let FormHelper = require('../shared/helpers/FormHelper');


describe('page.appearance making sure that pages look correct ||', function() {

	let page = new HomePage();
  let comment = new CommentSection();
  let login = new LoginHelper();

  let EC = protractor.ExpectedConditions;

	beforeEach(function(){
    page.get();
    var button = element(by.css('md-dialog-content button.terms_conditions__button'));
    browser.sleep(1000); //Literally no point in trying to make protractor better
    button.isPresent().then(function(result) {
        if ( result ) {
            button.click();
        } else {
          console.log('no terms button');
        }
    });
    
	});
  
    
  it('New user sees correct parts of home page', function() {
    login.logout();
    login.create();
        
    DomHelper.canInteractCheck(page.yourVotes);
    browser.wait(EC.textToBePresentInElement(page.yourVotes,"You haven't voted, yet."), 5000, "");
    
    DomHelper.canInteractCheck(page.yourComments);
    browser.wait(EC.textToBePresentInElement(page.yourComments,"You haven't commented, yet."), 5000, "Can not find 'A Commented On Motion' in the Your Comments section");
    

  });
  

  it('Not logged In see correct parts of home page', function() {
      login.logout();

      page.get('/');
      
      //See things that you need to see to vote
      DomHelper.canInteractCheck(page.topComments);
      browser.wait(EC.visibilityOf(page.topComments), 5000,"Can not see the top comments section");
      browser.wait(EC.textToBePresentInElement(page.topComments,"The Top Agree Comment Text"), 5000, "Can not find 'The Top Agree Commment' in the Top Comments section"); 
      
      DomHelper.canInteractCheck(page.topMotions);
      browser.wait(EC.visibilityOf(page.topMotions), 5000, "Can not see the top motions section");
      browser.wait(EC.textToBePresentInElement(page.topMotions,"A Top Motion"), 5000, "Can not find 'A Top Motion' in the Top Motion section");
      
      browser.wait(EC.invisibilityOf(page.yourVotes), 5000, "Can see your votes section despite being logged out");
      browser.wait(EC.invisibilityOf(page.yourComments), 5000, "Can see your comments section despite being logged out");
      
  });
  
  
  it('Logged in sees correct parts of home page', function() {
      login.logout();

      login.login('citizen@iserveu.ca');    
      
      let sentence = "I am. No good. "+faker.lorem.word();
      comment.voteAndWriteAComment(sentence);
      
      let page = new HomePage();
      page.get();

      DomHelper.canInteractCheck(page.yourVotes);
      browser.wait(EC.textToBePresentInElement(page.yourVotes,"A Commented On Motion"), 5000, "Can not find 'A Commented On Motion' in the Your Votes section");
      
      DomHelper.canInteractCheck(page.yourComments);
      browser.wait(EC.textToBePresentInElement(page.yourComments,"A Commented On Motion"), 5000, "Can not find 'A Commented On Motion' in the Your Comments section");
      browser.wait(EC.textToBePresentInElement(page.yourComments,sentence), 5000, "Can not find '"+sentence+"' in the Your Comments section");
  });
  


  afterEach(function(){
      let login = new LoginHelper();
      login.logout();
      
      ConsoleHelper.printErrors();
  });


});
