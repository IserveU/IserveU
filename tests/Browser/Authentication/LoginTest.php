<?php

namespace Tests\Browser;

use Tests\Browser\Pages\AuthenticationPage;
use Tests\DuskTestCase;
use Tests\DuskTools\Browser;

class LoginTest extends DuskTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setSettings(['site.terms.force'=>0]);
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
                    ->typeBetter('@email', 'admin@iserveu.ca')
                    ->typeBetter('@password', 'abcd1234')
                    ->press('@login')
                    ->waitForLocationContains('/#/home');
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
            $browser->logout()
                    ->visit(new AuthenticationPage())
                    ->typeBetter('@email', 'admisfddfsdfsn@iserveu.ca')
                    ->typeBetter('@password', 'abcsdfsdfsdfd1234')
                    ->press('@login')
                    ->waitForText('Password and email combination do not match.')
                    ->waitForText('Forgot password? Click here to Reset.');
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
            $browser->logout()
                      ->visit(new AuthenticationPage())->pause(3000)
                      ->typeBetter('@email', 'user@iserveu.ca')
                      ->typeBetter('@password', 'abcd1234')
                      ->press('@login')
                      ->waitForLocationContains('/#/home');
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
            $browser->logout()
                      ->visit(new AuthenticationPage())->pause(3000)
                      ->typeBetter('@email', 'citizen@iserveu.ca')
                      ->typeBetter('@password', 'abcd1234')

                      ->press('@login')
                      ->waitForLocationContains('/#/home');
        });
    }
}
