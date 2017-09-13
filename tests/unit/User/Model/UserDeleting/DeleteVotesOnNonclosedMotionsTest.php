<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class DeleteVotesOnNonclosedMotionsTest extends BrowserKitTestCase
{
    use DatabaseTransactions;

    public $userThatVoted;

    public function setUp()
    {
        parent::setUp();

        $this->userThatVoted = factory(App\User::class)->create();
    }

    /** @test **/
    public function deleted_user_will_have_votes_on_non_closed_motions_permanently_deleted()
    {
        $nonClosedMotionVotes = factory(App\Vote::class, 5)->create();
        foreach ($nonClosedMotionVotes as $vote) {
            $vote->user_id = $this->userThatVoted->id;
            $vote->save();
        }

        $this->userThatVoted->delete();

        $this->dontSeeInDatabase('votes', ['user_id' => $this->userThatVoted->id]);
    }

    /** @test **/
    public function deleted_user_will_not_have_votes_on_closed_motions_deleted()
    {
        $closedMotion = factory(App\Motion::class, 'closed')->create();
        $vote = factory(App\Vote::class)->create([
            'motion_id'   => $closedMotion->id,
        ]);

        $user = $vote->user;

        $this->seeInDatabase('votes', ['user_id' => $user->id]);
    }
}
