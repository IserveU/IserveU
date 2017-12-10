<?php

namespace Tests\Browser\Integration\Motion;

use App\Motion;
use Laravel\Dusk\Browser;
use Tests\Browser\Components\SidebarSearch;
use Tests\Browser\Pages\MotionPage;
use Tests\DuskTestCase;

class MotionSidebarTest extends DuskTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setSettings(['site.terms.force' => 0]);
    }

    /**
     * Motion sidebar. This test is so crappy because the design makes testing hard, probably better to wait for
     * design to not have two sidebars.
     *
     * @return void
     * @test
     **/
    public function can_click_filter_button()
    {
        $this->user = static::getPermissionedUser('create-motion');

        $this->motion = factory(Motion::class, 'closed')->create();

        $this->browse(function (Browser $browser) {
            $browser->visit(new MotionPage())
                    ->within(new SidebarSearch(), function ($browser) {
                        $browser->waitFor('@filterButton')
                                ->click('@filterButton');
                    })
                    ->waitForText('Filter by Departments');
        });
    }
}
