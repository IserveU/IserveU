// var MotionFormPage = require('../helpers/motion-form.page');
// var MotionPage = require('../helpers/motion.page');
// var UserLoginPage = require('../helpers/user-login.page');
// var Menu = require('../helpers/menu.page');
// var VoteButtons = require('../helpers/vote-buttons.page');
// // var CommentPage = require('./comment.page');
// var Helper = require('../helpers/helpers');


// xdescribe('administrators interaction with creating/editing/voting on a motion', function() {

//   it('should be able to login and see adminstration user nav stuff', function() {

//   	browser.get('#/login');

//     var login = new UserLoginPage();
//     var helper = new Helper();

//     login.login({
//       email: browser.params.login.username,
//       password: browser.params.login.password
//     });


//     browser.wait( helper.waitForUrlToChangeTo(/\/home/gi),
//       1000, 'Expectation error: Timed out waiting for home state change.' );

//     expect(browser.getLocationAbsUrl()).toEqual('/home');
//   });


//   it('should be able to publish a simple motion and details are correct', function() {

//     var menu = new Menu();
//     var motionForm = new MotionFormPage();
//     var helper = new Helper();
//     browser.ignoreSynchronization=true;
//     menu.dropdown.click();
//     browser.driver.sleep(500);
//     menu.createMotionButton.click();
//   	expect(browser.getLocationAbsUrl()).toEqual('/create-motion');

//     motionForm.fillBasic();
//     motionForm.setStatus('publish');
//     motionForm.saveButton.click();

//     var REGEX = /\/motion\/[0-9]+/gi;
//     browser.wait( helper.waitForUrlToChangeTo(REGEX),
//         1000, 'Expectation error: Timed out waiting for motion state change.' );

//     // TODO
//     // - motion tiles tests should reflect what was set.

//   });

//   // UNABLE TO GET MOTION TITLE?
//   // it('should be able to see a motion title', function() {
//   //   browser.get('http://iserveu.local/#/motion/35');


//   //   var motion = new MotionPage();

//   //   expect('0.v8qtqa9la8pmygb9').toEqual(motion.motionTitle.getText());

//   // })

//   it('should be able to vote on an open motion and change vote', function() {

//     var vote = new VoteButtons();
//     // var comment = new CommentPage();

//     // commentCreate should not be in view

//     // var originalAgreeCounter = vote.agreeCounter.getText();

//     vote.voteButtons.get(1).click();

//     expect(vote.voteButtons.get(1).icon).toBe('loading');

//     expect(vote.voteButtons.get(1).isEnabled()).toBe(false); // or maybe class name?

//     vote.voteButtons.get(2).click();
//     vote.voteButtons.get(0).click();

//     vote.agreeButton.click();

//     console.log(vote.agreeButton);

//     // expect(vote.agreeButton.isEnabled()).toBe(false); // probably won't work
//     // expect(vote.disagreeButton.isEnabled()).toBe(true);
//     // expect(vote.abstainButton.isEnabled()).toBe(true);




//     // agreeButton.click();
//     // agreeButton.toBe.disabled
//     // disagree and abstain able
//     // agreeCounter to have one more
//     // disagreeCounter should be same
//     // abstainCounter should be same
//     // commentColumn to be seen
//     //
//     // commentCreate should be seen
//     //
//     // disagreeButton.click()
//     // disagree should be disabled
//     // agree and abstain should be abled
//     // disagree should have one more
//     // agree should have one more
//     // disagreeCommentColumn should be seen
//     //
//     // abstainButton.click();
//     // abstain should be disabled
//     // agree and disagree should be abled
//     // abstain should have one more
//     // disagree should have one less
//     // agree should stay the same
//     // disagreeCommentColumn should be seenaac

//     // ping the backend and then count the number to be true , or maybe have this at the very end
//   });

//   // it('should not be able to vote an a closed motion', function() {
//   //   // change the motion to closed
//   //   // try to vote
//   //   // expect a fail
//   // });


//   // it('should be able to comment on an open motion && edit their motion', function() {


//   // });


//   // it('should ', function() {


//   // });

//   // it('should ', function() {


//   // });

//   // it('should ', function() {


//   // });


// });
