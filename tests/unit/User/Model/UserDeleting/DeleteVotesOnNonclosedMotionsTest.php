<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class DeleteActiveVotesTest extends TestCase
{
    use DatabaseTransactions;

    public $votingUser;

    public function setUp()
    {
        parent::setUp();

        $this->votingUser = factory(App\User::class)->create();
    }

    /** @test **/
    public function deleted_user_will_have_votes_on_non_closed_motions_deleted()
    {
        $nonClosedMotionVotes = factory(App\Vote::class, 5)->create();
        foreach ($nonClosedMotionVotes as $vote) {
            $vote->user_id = $this->votingUser->id;
            $vote->save();
        }

        $this->votingUser->delete();

        $this->dontSeeInDatabase('votes', ['user_id' => $this->votingUser->id]);
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
