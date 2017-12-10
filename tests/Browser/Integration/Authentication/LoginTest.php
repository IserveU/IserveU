<?php

namespace Tests\Browser\Integration\Authentication;

use Laravel\Dusk\Browser;
use Tests\Browser\Components\JavascriptMenuContainer;
use Tests\Browser\Components\LoginBox;
use Tests\Browser\Pages\AuthenticationPage;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setSettings(['site.terms.force' => 0]);
    }

    /**
     * Appearance iserveu first landing on login page as new user
     * Should see correct logo and title.
     *
     * @return void
     * @test
     **/
    public function login_as_user_with_correct_details()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new AuthenticationPage())
                    ->within(new LoginBox(), function ($browser) {
                        $browser->loginWith('admin@iserveu.ca', 'abcd1234');
                    })
                    ->waitForLocationContains('/#/home')
                    ->click('@cog')
                    ->within(new JavascriptMenuContainer('.md-open-menu-container'), function ($browser) {
                        $browser->seeOption('Logout Default');
                    });
        });
    }

    /**
     * Should see correct error message after login attempt
     * Should see forget password reminder after login attempt.
     *
     * @return void
     * @test
     **/
    public function fail_to_login_as_user_with_incorrect_details()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new AuthenticationPage())
                    ->within(new LoginBox(), function ($browser) {
                        $browser->loginWith('admisfddfsdfsn@iserveu.ca', 'abcsdfsdfsdfd1234');
                    }
            )
            ->waitForText('Password and email combination do not match.')
            ->waitForText('Forgot password? Click here to Reset.')
            ->assertSee('Forgot password? Click here to Reset.'); // So test doesn't give warn
        });
    }

    /**
     * Interactions for an unverified user with no roles
     * Should be able to login as unverified user.
     *
     * @return void
     * @test
     **/
    public function login_as_unverified_user()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new AuthenticationPage())
                      ->within(new LoginBox(), function ($browser) {
                          $browser->loginWith('user@iserveu.ca', 'abcd1234');
                      })
                      ->waitForLocationContains('/#/home')
                      ->click('@cog')
                      ->within(new JavascriptMenuContainer('.md-open-menu-container'), function ($browser) {
                          $browser->seeOption('Logout MrsUnverified');
                      });
        });
    }

    /**
     * Interactions for a verified user with citizen roles.
     *
     * @return void
     * @test
     **/
    public function login_as_citizen()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new AuthenticationPage())
                      ->within(new LoginBox(), function ($browser) {
                          $browser->loginWith('citizen@iserveu.ca', 'abcd1234');
                      })
                      ->waitForLocationContains('/#/home')
                      ->click('@cog')
                      ->within(new JavascriptMenuContainer('.md-open-menu-container'), function ($browser) {
                          $browser->seeOption('Logout MrsVerified');
                      });
        });
    }
}
