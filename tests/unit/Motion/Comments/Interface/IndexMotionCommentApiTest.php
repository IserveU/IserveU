<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class IndexMotionCommentApiTest extends BrowserKitTestCase
{
    use DatabaseTransactions;

    protected static $motion;

    public function setUp()
    {
        parent::setUp();

        if (is_null(static::$motion)) {
            static::$motion = factory(App\Motion::class, 'published')->create();

            $comments = factory(App\Comment::class, 4)->create();

            foreach ($comments as $comment) {
                $comment->vote->motion_id = static::$motion->id;
                $comment->vote->save();
            }

            //Each commenter likes a random comment
            foreach ($comments as $comment) {
                \App\CommentVote::create([
                    'comment_id' => $comments->random()->id,
                    'vote_id'    => $comment->vote_id,
                    'position'   => rand(-1, 1),
                ]);
            }
        }
    }

    ///////////////////////////////////////////////////////////CORRECT RESPONSES

    /** @test */
    public function default_filter()
    {
        $this->get('/api/motion/'.static::$motion->slug.'/comment')
                ->assertResponseStatus(200)
                ->seeJsonStructure([
                    'agreeComments',
                    'abstainComments',
                    'disagreeComments',
                ]);
    }

    /////////////////////////////////////////////////////////// INCORRECT RESPONSES
}
