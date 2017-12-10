<?php

namespace Tests\Browser\Integration\Authentication;

use App\Community;
use Laravel\Dusk\Browser;
use Tests\Browser\Components\JavascriptMenuContainer;
use Tests\Browser\Components\LoginBox;
use Tests\Browser\Pages\AuthenticationPage;
use Tests\DuskTestCase;

class RegisterTest extends DuskTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setSettings(['site.terms.force' => 0]);
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
        $user->community = $this->community; // For ease

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit(new AuthenticationPage())
                ->within(new LoginBox(), function ($browser) {
                    $browser->clickCreate();
                }
            )
                ->click('@community')
                ->within(new JavascriptMenuContainer('.md-select-menu-container'),
                function ($browser) use ($user) {
                    $browser->selectOption($user->community->name);
                }
            )
                ->type('@firstName', $user->first_name)
                ->type('@lastName', $user->last_name)
                ->type('@newEmail', $user->email)
                ->type('@confirmEmail', $user->email)
                ->type('@newPassword', 'abcd1234!!')
                ->press('@submitCreate')
                ->waitForLocationContains('/#/home', 30) // May not queue email on testing
                ->click('@cog')
                ->within(new JavascriptMenuContainer('.md-open-menu-container'), function ($browser) use ($user) {
                    $browser->seeOption('Logout '.$user->first_name);
                });
        });
    }
}
