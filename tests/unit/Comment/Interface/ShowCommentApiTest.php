<?php

include_once 'CommentApi.php';

use Illuminate\Foundation\Testing\DatabaseTransactions;

class ShowCommentApiTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
    }

    /////////////////////////////////////////////////////////// CORRECT RESPONSES

    /** @test */
    public function show_comment_test()
    {
        $comment = factory(App\Comment::class)->create();

        $this->signIn($comment->user);

        $this->visit('/api/comment/'.$comment->id)
            ->seeJsonStructure([
                'id', 'text',
            ])->dontSeeJson([

            ]);
    }
}
