<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class ShowVoteApiTest extends BrowserKitTestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
    }

    /////////////////////////////////////////////////////////// CORRECT RESPONSES

    /** @test */
    public function show_vote_test()
    {
        $vote = factory(App\Vote::class)->create();

        $this->signIn($vote->user);

        $this->visit('/api/vote/'.$vote->id)
            ->seeJsonStructure([
                'id', 'position', 'motion_id', 'deferred_to_id',
            ])->dontSeeJson([

            ]);
    }
}
