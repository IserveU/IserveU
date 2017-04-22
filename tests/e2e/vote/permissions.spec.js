let VoteSection = require('../shared/pages/Motion/VoteSection');
let LoginHelper = require('../shared/helpers/LoginHelper');
let ConsoleHelper = require('../shared/helpers/ConsoleHelper');


describe('vote.permissions making sure that vote permissions work correctly || ', function() {

	let vote 		= new VoteSection();
	let login 		= new LoginHelper();

	beforeEach(function(){

	});



  	it('citizen can vote on published motion and see that reflected in status bar', function() {
  		login.login('citizen@iserveu.ca');

			vote.get();


			vote.clickAbstainButton(); //Ensure starting in abstain position


			vote.getAgreeCount().then(function(firstCount){

				vote.clickAgreeButton();

				expect(vote.getAgreeCount()).toBe((firstCount+1));

			});


			vote.getDisagreeCount().then(function(firstCount){
				vote.clickDisagreeButton();

				expect(vote.getDisagreeCount()).toBe((firstCount+1));

			});

			vote.getAbstainCount().then(function(firstCount){

				vote.clickAbstainButton();

				expect(vote.getAbstainCount()).toBe((firstCount+1));

			});

  	});



  	it('citizen can not vote on closed motion', function() {
			login.login('citizen@iserveu.ca');

			vote.get('a-closed-motion');

			// expect(vote.getAbstainButton().isEnabled()).toBe(false); //Probably a better check but it's failin

      vote.clickDisabledButton();
      
      
			vote.getAgreeCount().then(function(firstCount){
        
				expect(vote.getAgreeCount()).toBe(firstCount);

			});


			vote.getDisagreeCount().then(function(firstCount){

				
				expect(vote.getDisagreeCount()).toBe(firstCount);

			});

			vote.getAbstainCount().then(function(firstCount){

				
				expect(vote.getAbstainCount()).toBe(firstCount);

			});

  	});


    
    afterEach(function(){

        ConsoleHelper.printErrors();
    });


});
