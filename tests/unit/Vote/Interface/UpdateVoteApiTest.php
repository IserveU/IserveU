<?php

include_once 'VoteApi.php';

use Illuminate\Foundation\Testing\DatabaseTransactions;

class UpdateVoteApiTest extends VoteApi
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
        $this->signInAsRole('administrator');


        $this->modelToUpdate = factory(App\Vote::class)->create([
            'user_id'   => $this->user->id,
        ]);

        $this->route = '/api/vote/';
    }

    /** @test  ******************/
    public function update_vote_with_position()
    {
        $this->updateFieldsGetSee(['position'], 200);
    }

    /////////////////////////////////////////////////////////// INCORRECT RESPONSES

    /** @test  ******************/
    public function update_vote_with_motion_id_fails()
    {
        $motion = factory(App\Motion::class)->create();

        $this->updateContentGetSee([
            'position'      => -1,
            'motion_id'     => $motion->id,
        ], 400);
    }

    /** @test  ******************/
    public function update_vote_with_user_id_fails()
    {
        $this->updateContentGetSee([
            'position'    => -1,
            'user_id'     => $this->user->id,
        ], 400);
    }

    /** @test  ******************/
    public function update_vote_with_invalid_position_values_fails()
    {
        $this->updateContentGetSee([
            'position'     => 2,
        ], 400);

        $this->updateContentGetSee([
            'position'     => -2,
        ], 400);
    }

    /** @test  ******************/
    public function update_vote_with_non_numeric_position_fails()
    {
        $this->updateContentGetSee([
            'position'     => 'yes',
        ], 400);

        $this->updateContentGetSee([
            'position'     => [1],
        ], 400);
    }

    /** @test  ******************/
    public function update_vote_with_empty_position_fails()
    {
        $this->updateContentGetSee([
            'position'     => '',
        ], 400);

        $this->updateContentGetSee([
            'position'     => null,
        ], 400);
    }
}
