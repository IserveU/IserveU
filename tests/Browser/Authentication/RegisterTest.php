<?php

namespace Tests\Browser;

use App\Community;
use Tests\Browser\Pages\AuthenticationPage;
use Tests\DuskTestCase;
use Tests\DuskTools\Browser;

class RegisterTest extends DuskTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setSettings(['site.terms.force'=>0]);
    }

    /**
     * Should be able to signup for site.
     *
     * @return void
     * @test
     **/
    public function signup_for_site_with_correct_details()
    {
        $this->community = factory(Community::class)->create();

        $user = factory(\App\User::class)->make();
        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit(new AuthenticationPage())
                  ->clickBetter('@create')
                  ->jsSelectList('@community', $this->community->name, 'md-option', 'md-select-menu')
                  ->typeBetter('@firstName', $user->first_name)
                  ->typeBetter('@lastName', $user->last_name)
                  ->typeBetter('@newEmail', $user->email)
                  ->typeBetter('@confirmEmail', $user->email)
                  ->typeBetter('@newPassword', 'abcdfsadf!!!!sdf');

            $browser->press('@submitCreate')

                  ->waitForLocationContains('/#/home', 15);
        });
    }

    /*
      	// it('', function() {
    //     let email     = faker.internet.email();
    //     page.clickCreateButton();
    //
      	// 	  let formHelper = new FormHelper(
    //       page.getCreateButton(),
    //       new Map([
    //         ["newemail",email],
    //         ['confirmemail', email],
    //         ['firstname', faker.name.firstName()],
    //         ['lastname', faker.name.lastName()],
    //         ['newpassword', faker.internet.password()]
    //       ])
    //     );
    //
       // 		  formHelper.selectBox('register.values.community_id',"Yellowknife");
    //
    //  		formHelper.submit();
    //
    		// 		//Failed on January the 17 (URL did not match)
    //  		browser.wait(EC.urlContains('home'), 12000,"Url did not match");
    //
      	// }); */
}
