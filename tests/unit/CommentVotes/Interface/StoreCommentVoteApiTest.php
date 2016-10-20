<?php

include_once 'CommentVoteApi.php';

use Illuminate\Foundation\Testing\DatabaseTransactions;

class StoreCommentVoteApiTest extends CommentVoteApi
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->signInAsRole('administrator');

        $this->vote = $this->setupCommentVote();

        //I'll got to hell for this route
        $this->route = '/api/comment/'.$this->vote->motion->comments->first()->id.'/comment_vote/';
    }

    /** @test  ******************/
    public function store_commentvote_with_position()
    {
        //Cant use the automated system on these
        $this->storeFieldsGetSee(['position'], 200);
    }

    /////////////////////////////////////////////////////////// INCORRECT RESPONSES

    /** @test  ******************/
    public function store_commentvote_with_motion_id_fails()
    {
        $motion = factory(App\Motion::class)->create();
        $this->storeContentGetSee([
            'position'      => 1,
            'motion_id'     => $motion->id,
        ], 400);
    }

    /** @test  ******************/
    public function store_commentvote_with_vote_id_fails()
    {
        $this->storeFieldsGetSee([
            'position'      => -1,
            'vote_id'       => $this->vote->id,
        ], 400);
    }

    /** @test  ******************/
    public function store_commentvote_with_no_position_fails()
    {
        $this->storeContentGetSee([
            'position'      => '',
        ], 400);

        $this->storeContentGetSee([
            'position'      => null,
        ], 400);
    }
}
