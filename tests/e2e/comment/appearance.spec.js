let CommentSection 	= require('../shared/pages/Motion/CommentSection');
let LoginHelper 	= require('../shared/helpers/LoginHelper');
let faker = require('faker');
let ConsoleHelper = require('../shared/helpers/ConsoleHelper');

describe('comment.appearance making sure that votes display correctly || ', function() {

	let comment 	= new CommentSection();
	let login 		= new LoginHelper();

	beforeEach(function(){

	});


  it('should see own agreeing comment', function() {
  	
    login.login('citizen@iserveu.ca');

		comment.get('a-published-motion');

		comment.clickAgreeButton();

		expect(comment.getUserCommentTitle('text')).toBe("Why Did You Agree?");

		let commentText = faker.lorem.sentences(4);

		comment.setAndSaveUserComment(commentText);

		comment.expectSectionActive('agree');
		comment.expectSectionInactive(['disagree','abstain']);
    comment.expectCommentListContainsComment('agree',[commentText,"MrsVerified"]);

		comment.clickDisagreeSection();

		comment.expectSectionInactive(['agree','abstain']);
		comment.expectSectionActive('disagree');


  });

  afterEach(function(){
      let login = new LoginHelper();
      login.logout();
      ConsoleHelper.printErrors();
  });


});
