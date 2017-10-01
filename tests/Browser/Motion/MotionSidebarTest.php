<?php

namespace Tests\Browser\Motion;

use App\Motion;
use Tests\Browser\Pages\SidebarSection;
use Tests\DuskTestCase;
use Tests\DuskTools\Browser;

class MotionSidebarTest extends DuskTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setSettings(['site.terms.force'=>0]);
    }

    /**
     * Motion sidebar lets you filter by closed, published.
     *
     * @return void
     * @test
     **/
    public function can_filter_motions_by_different_status()
    {
        $this->user = static::getPermissionedUser('create-motion');

        $this->motion = factory(Motion::class, 'closed')->create();

        $this->browse(function (Browser $browser) {
            $browser->visit(new SidebarSection())
                    ->clickBetter('@filterButton')
                    ->waitFor('@motionStatusFilterSelect');

            $this->markTestSkipped('Cannot select md-option for some reason');
            // ->jsSelectList('@motionStatusFilterSelect','Closed');

            $closedOption = $browser->findTagContainingText('md-option', 'Closed');

            $browser->clickBetter($closedOption);
        });
    }
}
