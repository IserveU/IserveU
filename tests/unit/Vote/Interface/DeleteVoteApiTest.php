<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class DeleteVoteApiTest extends BrowserKitTestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
    }

    /////////////////////////////////////////////////////////// CORRECT RESPONSES

    /** @test  ******************/
    public function delete_vote_correct_response()
    {
        $this->signInAsRole('administrator');

        $vote = factory(App\Vote::class)->create();

        $this->delete('/api/vote/'.$vote->id)
            ->assertResponseStatus(200);
    }

    /////////////////////////////////////////////////////////// INCORRECT RESPONSES
}
