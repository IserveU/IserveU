<?php

namespace Tests\Browser;

use Faker\Factory;
use Tests\Browser\Pages\AuthenticationPage;
use Tests\DuskTestCase;
use Tests\DuskTools\Browser;

class TermsAndConditionsTest extends DuskTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * Makes sure that when site settings are created they show as expected.
     *
     * @return void
     * @test
     **/
    public function terms_and_conditions_appear_with_setting()
    {
        $faker = Factory::create();

        $sentence = $faker->sentence();
        $this->setSettings(['site.terms.force'=>1, 'site.terms.text'=>$sentence]);

        $this->browse(function (Browser $browser) use ($sentence) {
            $browser->visit(new AuthenticationPage())
                    ->assertSeeInBetter('@termsAndConditions', $sentence);
        });
    }

    /**
     * Makes sure that when site settings are created they show as expected.
     *
     * @return void
     * @test
     **/
    public function terms_and_conditions_disappear_with_setting()
    {
        $faker = Factory::create();

        $sentence = $faker->sentence();
        $this->setSettings(['site.terms.force'=>0]);

        $this->browse(function (Browser $browser) use ($sentence) {
            $browser->visit(new AuthenticationPage())
                    ->waitFor('@login')
                    ->assertMissing('@termsAndConditions');
        });
    }
}
