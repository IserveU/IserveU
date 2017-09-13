<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class DeleteSoftDeletedVotesTest extends BrowserKitTestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
    }

    /** @test **/
    public function permanently_delete_soft_deleted_votes_on_deleted_user()
    {
        $vote = factory(App\Vote::class)->create();
        $user = $vote->user;
        $vote->delete();

        $this->seeInDatabase('votes', ['id' => $vote->id]);

        $user->delete();
        $this->dontSeeInDatabase('votes', ['id' => $vote->id]);
    }

    /** @test **/
    public function do_not_permanently_delete_votes_that_havent_been_soft_deleted()
    {
        $vote = factory(App\Vote::class, 'on_closed')->create();

        $user = $vote->user;

        $user->delete();
        $this->seeInDatabase('votes', ['id' => $vote->id]);
    }
}
