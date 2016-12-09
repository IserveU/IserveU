<?php

include_once 'VoteApi.php';

use Illuminate\Foundation\Testing\DatabaseTransactions;

class StoreVoteApiTest extends VoteApi
{
    use DatabaseTransactions;

    protected $class = App\Vote::class;

    protected $modelToUpdate;

    public function setUp()
    {
        parent::setUp();
        $this->motion = factory(App\Motion::class, 'published')->create();
        $this->route = '/api/motion/'.$this->motion->id.'/vote/';

        $this->signInAsRole('administrator');
    }

    /** @test  ******************/
    public function store_vote_with_position()
    {
        $this->storeFieldsGetSee(['position'], 200);
    }

    /////////////////////////////////////////////////////////// INCORRECT RESPONSES

    /** @test  ******************/
    public function store_vote_with_motion_id_fails()
    {
        $motion = factory(App\Motion::class)->create();
        $this->storeContentGetSee([
            'position'      => -1,
            'motion_id'     => $motion->id,
        ], 400);
    }

    /** @test  ******************/
    public function store_vote_with_user_id_fails()
    {
        $this->storeContentGetSee([
            'position'    => -1,
            'user_id'     => $this->user->id,
        ], 400);  //It would also get a 403
    }

    /** @test  ******************/
    public function store_vote_with_empty_position_fails()
    {
        $this->storeContentGetSee([
            'position'     => '',
        ], 400);

        $this->storeContentGetSee([
            'position'     => null,
        ], 400);
    }
}
