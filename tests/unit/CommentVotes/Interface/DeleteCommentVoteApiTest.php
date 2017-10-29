<?php

include_once 'CommentVoteApi.php';

use Illuminate\Foundation\Testing\DatabaseTransactions;

class DeleteCommentVoteApiTest extends BrowserKitTestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
    }

    /////////////////////////////////////////////////////////// CORRECT RESPONSES

    /** @test  ******************/
    public function administrator_delete_commentvote_correct_response()
    {
        $this->signInAsRole('administrator');

        $commentvote = factory(App\CommentVote::class)->create();

        $this->delete('/api/comment_vote/'.$commentvote->id)
            ->assertResponseStatus(403);
    }

    /** @test  ******************/
    public function delete_commentvote_correct_response()
    {
        $this->signIn();

        //3rd party comment
        $comment = factory(App\Comment::class)->create();

        // User has also voted on this motion
        $vote = factory(App\Vote::class)->create([
            'user_id'   => $this->user->id,
            'motion_id' => $comment->vote->motion_id,
        ]);

        //And voted on a comment
        $commentvote = factory(App\CommentVote::class)->create([
            'vote_id' => $vote->id,
        ]);

        $this->delete('/api/comment_vote/'.$commentvote->id)
            ->assertResponseStatus(200);
    }

    /////////////////////////////////////////////////////////// INCORRECT RESPONSES
}
