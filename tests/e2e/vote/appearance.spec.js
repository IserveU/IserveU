let VoteSection 	= require('../shared/pages/Motion/VoteSection');
let LoginHelper 	= require('../shared/helpers/LoginHelper');
let SidebarSection 	= require('../shared/pages/Motion/SidebarSection');


describe('vote.appearance making sure that votes display correctly || ', function() {

	let vote 		= new VoteSection();
	let login 		= new LoginHelper();
	let sidebar  	= new SidebarSection();

	beforeEach(function(){

	});

 
  	it('the passing status icon should match', function() {
  		login.login('citizen@iserveu.ca'); 		
	
		sidebar.clickRandomMotion();

		vote.voteRandomWay(); //Ensure starting in abstain position

		browser.driver.sleep(2000); //This verification after this point failed randomly 2016-12-4

		return vote.getCounts().then(function(counts){
			let passingStatusIcon = vote.getPassingStatusIcon();

			if(counts.agree>counts.disagree){
				expect(passingStatusIcon.getAttribute('md-svg-src')).toBe('thumb-up');

			} else if (counts.agree<counts.disagree){
				expect(passingStatusIcon.getAttribute('md-svg-src')).toBe('thumb-down');

			} else {
				//Has failed with Expected 'thumbs-up-down' to be 'thumb-up'. several times 2016-12-10
				console.log("Agree:"+counts.agree+ " Disagree:"+counts.disagree + " Abstain:"+counts.abstain);
				expect(passingStatusIcon.getAttribute('md-svg-src')).toBe('thumbs-up-down');

			}

		});



  	});

  	

});
