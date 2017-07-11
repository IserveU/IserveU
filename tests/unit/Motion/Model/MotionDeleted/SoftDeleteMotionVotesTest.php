<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class SoftDeleteMotionVotesTest extends BrowserKitTestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
    }

    /** @test **/
    public function votes_on_motion_get_hard_deleted()
    {
        $motion = factory(App\Vote::class)->create()->motion;

        $motion->delete();

        $this->seeInDatabase('votes', ['motion_id' => $motion->id]);
        $this->dontSeeInDatabase('votes', ['motion_id' => $motion->id, 'deleted_at' => null]);
    }
}
