<?php

namespace Tests\Browser\Motion;

use App\Motion;
use App\User;
use Tests\DuskTestCase;

class MotionSmokeTest extends DuskTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setSettings(['site.terms.force'=>0]);
    }

    /**
     * Tests a user creating, editing and then submitting a motion.
     * Then coming back later, finding it, and deciding to delete it.
     *
     * @return void
     * @test
     **/
    public function user_can_navigate_to_create_submit_see_motion_in_own_motion_list_then_delete_it()
    {
        $this->markTestSkipped('Need to implement full testing sequence');
    }
}
