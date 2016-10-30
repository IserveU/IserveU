<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class HardDeleteEmptyUserTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
    }

    /** @test */
    public function hard_delete_empty_user()
    {
        $user = factory(App\User::class)->create();

        $user->delete();
        $this->dontSeeInDatabase('users', ['id' => $user->id]);
    }

    /** @test */
    public function soft_delete_user_with_votes_on_closed_motion()
    {
        $user = factory(App\User::class)->create();
        $motion = factory(App\Motion::class, 'closed')->create();

        factory(App\Vote::class)->create([
            'user_id'       => $user->id,
            'motion_id'     => $motion->id,
        ]);

        $user->delete();
        $this->seeInDatabase('users', ['id' => $user->id]);
    }

    /** @test */
    public function soft_delete_user_with_closed_motion()
    {
        $user = factory(App\User::class)->create();
        $motion = factory(App\Motion::class, 'closed')->create([
            'user_id'   => $user->id,
        ]);

        $motion->delete();
        $this->seeInDatabase('users', ['id' => $user->id]);
    }
}
