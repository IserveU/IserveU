<?php

include_once 'CommentVoteApi.php';

use Illuminate\Foundation\Testing\DatabaseTransactions;

class ShowCommentVoteApiTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
    }

    /////////////////////////////////////////////////////////// CORRECT RESPONSES

    /** @test */
    public function show_commentvote_test()
    {
        $commentvote = factory(App\CommentVote::class)->create();

        $this->signIn($commentvote->vote->user);

        $this->visit('/api/comment_vote/'.$commentvote->id)
            ->seeJsonStructure([
                'id',
            ])->dontSeeJson([

            ]);
    }
}
