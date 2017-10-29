<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class DeleteNonclosedMotionsTest extends BrowserKitTestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
    }

    /** @test **/
    public function deleted_user_will_have_non_closed_motions_deleted()
    {
        $nonClosedMotions = factory(App\Motion::class, 5)->create();
        $user = factory(App\User::class)->create();

        foreach ($nonClosedMotions as $motion) {
            $motion->user_id = $user->id;
            $motion->save();
        }

        $user->delete();

        foreach ($nonClosedMotions as $motion) {
            $this->dontSeeInDatabase('motions', ['id' => $motion->id]);
        }
    }

    /** @test **/
    public function deleted_user_will_not_have_closed_motions_deleted()
    {
        $user = factory(App\User::class)->create();

        $closedMotion = factory(App\Motion::class, 'closed')->create([
            'user_id' => $user->id,
        ]);

        $user->delete();

        $this->seeInDatabase('motions', ['id' => $closedMotion->id]);
    }
}
