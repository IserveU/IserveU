<?php

namespace Tests\Browser\Integration\Authentication;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\AuthenticationPage;
use Tests\DuskTestCase;

class AppearanceTest extends DuskTestCase
{
    /**
     * Appearance iserveu first landing on login page as new user
     * Should see correct logo and title.
     *
     * @return void
     * @test
     **/
    public function authentication_page_has_correct_defaults()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new AuthenticationPage())
                    ->assertTitle('IserveU - eDemocracy')
                    ->waitFor('@logo')
                    ->assertVisible('@logo');
        });
    }
}
