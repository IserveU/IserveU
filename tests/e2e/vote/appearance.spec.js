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

		browser.getCurrentUrl().then(function(url){
			console.log(url);
		});

		browser.driver.sleep(5000); //This verification after this point failed randomly 2016-12-4

		return vote.getCounts().then(function(counts){
			let passingStatusIcon = vote.getPassingStatusIcon();

			if(counts.agree>counts.disagree){

				console.log("Agree:"+counts.agree+ " Disagree:"+counts.disagree + " Abstain:"+counts.abstain);
				expect(passingStatusIcon.getAttribute('md-svg-src')).toBe('thumb-up');

			} else if (counts.agree<counts.disagree){

				console.log("Agree:"+counts.agree+ " Disagree:"+counts.disagree + " Abstain:"+counts.abstain);
				//This failed too on 2017-01-06
				expect(passingStatusIcon.getAttribute('md-svg-src')).toBe('thumb-down');

			} else if (counts.agree==counts.disagree){
				// Has failed with Expected 'thumb-up' to be 'thumbs-up-down'. several times 2016-12-10
				// Could be due to not finding the element as when the CSS was totally changed it resolved here
				console.log("Agree:"+counts.agree+ " Disagree:"+counts.disagree + " Abstain:"+counts.abstain);
				expect(passingStatusIcon.getAttribute('md-svg-src')).toBe('thumbs-up-down');

			} else {
				expect(counts).toEqual("Not finding counts");
			}

		});



  	});



});
