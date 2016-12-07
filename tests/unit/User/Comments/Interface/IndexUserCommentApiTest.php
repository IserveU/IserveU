<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class IndexUserCommentApiTest extends TestCase
{
    use DatabaseTransactions;

    protected static $commentingUser;

    public function setUp()
    {
        parent::setUp();

        if (is_null(static::$commentingUser)) {
            static::$commentingUser = factory(App\User::class)->create();

            $comments = factory(App\Comment::class, 10)->create();

            foreach ($comments as $comment) {
                $comment->vote->user_id = static::$commentingUser->id;
                $comment->vote->save();
            }
        }

        $this->signIn(static::$commentingUser);
    }

    ///////////////////////////////////////////////////////////CORRECT RESPONSES

    /** @test */
    public function default_user_comment_filter()
    {
        $this->get('/api/user/'.static::$commentingUser->id.'/comment')
                ->assertResponseStatus(200)
                ->seeJsonStructure([
                   '*' => ['id', 'text', 'commentRank'],
                ]);
    }

    /////////////////////////////////////////////////////////// INCORRECT RESPONSES
}
