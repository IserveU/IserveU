<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class IndexUserCommentVoteApiTest extends TestCase
{
    use DatabaseTransactions;


    protected static $userCommentVoting;

    public function setUp()
    {
        parent::setUp();


        if (is_null(static::$userCommentVoting)) {
            $motion = $this->getStaticMotion();

            $vote = factory(App\Vote::class)->create([
                'motion_id' => $motion->id,
            ]);

            foreach ($motion->comments as $comment) {
                \App\CommentVote::create([
                    'comment_id'    => $comment->id,
                    'vote_id'       => $vote->id,
                    'position'      => rand(-1, 1),
                ]);
            }

            static::$userCommentVoting = $vote->user;
            \DB::commit();
        }

        $this->signIn(static::$userCommentVoting);
    }

    ///////////////////////////////////////////////////////////CORRECT RESPONSES

    /** @test */
    public function default_user_comment_vote_filter()
    {
        $this->get('/api/user/'.static::$userCommentVoting->id.'/comment_vote')
             ->seeJsonStructure([
                '*' => ['id', 'position', 'comment_id', 'vote_id', 'created_at'],
            ]);
    }

    /** @test */
    public function by_motion_user_comment_vote_filter_in()
    {
        $motion = $this->getStaticMotion();


        $commentVoteIds = static::$userCommentVoting->commentVotes->pluck('id')->toArray();

        $this->json('GET', '/api/user/'.static::$userCommentVoting->id.'/comment_vote', ['motion_id' => $motion->id]);

        foreach ($commentVoteIds as $commentVoteId) {
            $this->see($commentVoteId);
        }
    }

    /** @test */
    public function by_motion_user_comment_vote_filter_out()
    {
        $motion = factory(App\Motion::class, 'published')->create();

        $this->json('GET', '/api/user/'.static::$userCommentVoting->id.'/comment_vote', ['motion_id' => $motion->id])
                ->seeJson([]);
    }

    /////////////////////////////////////////////////////////// INCORRECT RESPONSES
}
