let CommentSection 	= require('../shared/pages/Motion/CommentSection');
let LoginHelper 	= require('../shared/helpers/LoginHelper');
let faker = require('faker');

describe('vote.appearance making sure that votes display correctly || ', function() {

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

		comment.expectSectionActive('disagree');
		comment.expectSectionInactive(['agree','abstain']);

		comment.clickAgreeSection();

		comment.expectSectionInactive(['disagree','abstain']);
		comment.expectSectionActive('agree');

		comment.expectCommentListContainsComment('agree',[commentText,"MrsVerified"]);


  });

  afterEach(function(){
      browser.manage().logs().get('browser').then(function(browserlog){
       // expect(browserlog.length).toEqual(0);
        if(browserlog.length) console.error(JSON.stringify(browserlog));
      });
  });


});
