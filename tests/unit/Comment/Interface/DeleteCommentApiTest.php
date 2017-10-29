<?php

include_once 'CommentApi.php';

use Illuminate\Foundation\Testing\DatabaseTransactions;

class DeleteCommentApiTest extends BrowserKitTestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
    }

    /////////////////////////////////////////////////////////// CORRECT RESPONSES

    /** @test  ******************/
    public function administrator_delete_comment_correct_response()
    {
        $this->signInAsRole('administrator');

        $comment = factory(App\Comment::class)->create();

        $this->delete('/api/comment/'.$comment->id)
            ->assertResponseStatus(403);
    }

    /** @test  ******************/
    public function delete_comment_correct_response()
    {
        $this->signIn();

        $vote = factory(App\Vote::class)->create([
            'user_id' => $this->user->id,
        ]);

        $comment = factory(App\Comment::class)->create([
            'vote_id' => $vote->id,
        ]);

        $this->delete('/api/comment/'.$comment->id)
            ->assertResponseStatus(200);
    }

    /////////////////////////////////////////////////////////// INCORRECT RESPONSES
}
