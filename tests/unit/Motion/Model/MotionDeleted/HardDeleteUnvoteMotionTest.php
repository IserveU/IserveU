<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class HardDeleteUndeletedMotionTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
    }

    /** @test **/
    public function motion_with_no_votes_gets_hard_deleted()
    {
        $motion = factory(App\Motion::class)->create();

        $motion->delete();

        $this->dontSeeInDatabase('motions', ['id' => $motion->id]);
    }

    /** @test **/
    public function motion_with_votes_gets_soft_deleted()
    {
        $motion = factory(App\Vote::class)->create()->motion;

        $motion->delete();

        $this->seeInDatabase('motions', ['id' => $motion->id]);
    }
}
