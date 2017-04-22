let VoteSection 	= require('../shared/pages/Motion/VoteSection');
let LoginHelper 	= require('../shared/helpers/LoginHelper');
let SidebarSection 	= require('../shared/pages/Motion/SidebarSection');
let MotionPage = require('../shared/pages/Motion/ShowMotionPage');
let ConsoleHelper = require('../shared/helpers/ConsoleHelper');


describe('vote.appearance making sure that votes display correctly || ', function() {

	let vote 		= new VoteSection();
	let login 	= new LoginHelper();
	let sidebar = new SidebarSection();
  let motion  = new MotionPage();

	beforeEach(function(){

	});


  it('the passing status icon should match', function() {
  	login.login('citizen@iserveu.ca');
    var EC = protractor.ExpectedConditions;

		sidebar.clickRandomMotion();

		//Failed on Jan-17:  No element found using locator: By(css selector, .motion_vote_buttons__button--abstain)
		vote.voteRandomWay(); //Ensure starting in abstain position

		browser.getCurrentUrl().then(function(url){
			console.log(url);
		});

		browser.waitForAngular();
    
    browser.sleep(2000); //The code below was evaluating to "Loading"

		return vote.getCounts().then(function(counts){
			let passingStatusIcon = vote.getPassingStatusIcon();

			if(counts.agree>counts.disagree){

				console.log("Agree:"+counts.agree+ " Disagree:"+counts.disagree + " Abstain:"+counts.abstain);
				expect(passingStatusIcon.getAttribute('md-svg-src')).toBe('thumb-up');

			} else if (counts.agree<counts.disagree){

				console.log("Agree:"+counts.agree+ " Disagree:"+counts.disagree + " Abstain:"+counts.abstain);
				//This failed too on 2017-01-06 && 2017-03-01
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

  it('Voting with URL should match', function() {
    login.login('citizen@iserveu.ca');

		var EC = protractor.ExpectedConditions;

    motion.get('a-published-motion');
    vote.clickAbstainButton();

		vote.getCounts().then(function(counts){

			//Failed randomly Jan 18th
			console.log("counts gathered");
			console.log(counts);
			browser.get('/#/motion/a-published-motion/vote/agree');

			browser.waitForAngular();

			expect(vote.getAgreeCount()).toBe(counts.agree+1);
			expect(vote.getDisagreeCount()).toBe(counts.disagree);

			browser.get('/#/motion/a-published-motion/vote/disagree');

			browser.waitForAngular();

			expect(vote.getAgreeCount()).toBe(counts.agree);
			expect(vote.getDisagreeCount()).toBe(counts.disagree+1);
		});

  });
  
  
    afterEach(function(){

        ConsoleHelper.printErrors();
    });


});
