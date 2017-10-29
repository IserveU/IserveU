<?php

include_once 'CommentApi.php';

use Illuminate\Foundation\Testing\DatabaseTransactions;

class UpdateCommentApiTest extends commentApi
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->modelToUpdate = factory(App\Comment::class)->create();

        $this->signIn($this->modelToUpdate->vote->user);

        $this->route = '/api/comment/';
    }

    /** @test  ******************/
    public function update_comment_with_text()
    {
        $this->updateFieldsGetSee(['text'], 200);
    }

    /** @test  ******************/
    public function update_comment_with_status()
    {
        $this->updateFieldsGetSee(['text', 'status'], 200);
    }

    /////////////////////////////////////////////////////////// INCORRECT RESPONSES

    /** @test  ******************/
    public function update_comment_with_motion_id_fails()
    {
        $motion = factory(App\Motion::class)->create();

        $this->updateContentGetSee([
            'text'      => 'You cant store on a motion directly',
            'motion_id' => $motion->id,
        ], 400);
    }

    /** @test  ******************/
    public function update_comment_with_vote_id_fails()
    {
        $this->updateContentGetSee([
            'text'    => 'The routes sets the vote',
            'vote_id' => $this->modelToUpdate->vote->id,
        ], 400);
    }

    /** @test  ******************/
    public function update_comment_with_no_text_fails()
    {
        $this->updateContentGetSee([
            'text' => '',
        ], 400);
    }
}
