<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class MotionCommentApiTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->signIn();
    }

    /*****************************************************************
    *
    *                   Basic CRUD functions:
    *
    ******************************************************************/

    /** @test  */
    public function user_id_is_not_visible()
    {
        $comment = factory(App\Comment::class)->create();


        $this->get('/api/motion/'.$comment->vote->motion_id.'/comment')
             ->assertResponseStatus(200)
             ->dontSeeJson([
                'user_id'   => $comment->vote->user_id,
            ]);
    }

    /** @test */
    public function it_shows_agree_comments_correctly()
    {
        $comment = factory(App\Comment::class)->create();

        $comment->vote->position = 1;
        $comment->push();

        $this->get('/api/motion/'.$comment->motion->slug.'/comment');

        $this->assertResponseStatus(200);

        $this->seeJsonStructure([
            'agreeComments' => [
                $comment->id => ['id', 'text'], //Keyed by ID
            ],
            'abstainComments'  => [],
            'disagreeComments' => [],
        ]);
    }

    /** @test */
    public function it_shows_disagree_comments()
    {
        $comment = factory(App\Comment::class)->create();

        $comment->vote->position = -1;
        $comment->push();

        $this->get('/api/motion/'.$comment->motion->slug.'/comment');

        $this->assertResponseStatus(200);

        $this->seeJsonStructure([
            'agreeComments'    => [],
            'abstainComments'  => [],
            'disagreeComments' => [
                $comment->id => ['id', 'text'], //Keyed by ID
            ],
        ]);
    }

    /** @test */
    public function it_shows_abstain_comments()
    {
        $comment = factory(App\Comment::class)->create();

        $comment->vote->position = 0;
        $comment->push();

        $this->get('/api/motion/'.$comment->motion->slug.'/comment');

        $this->assertResponseStatus(200);

        $this->seeJsonStructure([
            'agreeComments'   => [],
            'abstainComments' => [
                $comment->id => ['id', 'text'], //Keyed by ID
            ],
            'disagreeComments' => [],
        ]);
    }

    /** @test */
    public function it_can_get_all_motion_comments()
    {
        $motion = factory(App\Motion::class, 'published')->create();


        $positiveVote = factory(App\Vote::class)->create([
            'motion_id' => $motion->id,
            'position'  => 1,
        ]);

        $positiveComment = factory(App\Comment::class)->create([
            'vote_id'   => $positiveVote->id,
        ]);

        $negativeVote = factory(App\Vote::class)->create([
            'motion_id' => $motion->id,
            'position'  => -1,
        ]);

        $negativeComment = factory(App\Comment::class)->create([
            'vote_id'   => $negativeVote->id,
        ]);

        $abstainVote = factory(App\Vote::class)->create([
            'motion_id' => $motion->id,
            'position'  => 0,
        ]);

        $abstainComment = factory(App\Comment::class)->create([
            'vote_id'   => $abstainVote->id,
        ]);

        $this->get('/api/motion/'.$motion->id.'/comment');

        $this->assertResponseStatus(200);

        $this->seeJsonStructure([
            'agreeComments' => [
                '*' => ['id', 'text'],
            ],
            'abstainComments' => [
                '*' => ['id', 'text'],
            ],
            'disagreeComments' => [
                '*' => ['id', 'text'],
            ],
        ]);
    }

    /** @test */
    public function changing_vote_shows_changed_comments()
    {
        $vote = factory(App\Vote::class)->create([
            'user_id'   => $this->user->id,
            'position'  => 1,
        ]);

        $comment = factory(App\Comment::class)->create([
            'vote_id'   => $vote->id,
        ]);

        $this->get('/api/motion/'.$vote->motion_id.'/comment')
            ->assertResponseStatus(200);

        $response = json_decode($this->response->getContent(), true);

        $this->assertEquals(count($response['agreeComments']), 1);
        $this->assertEquals(count($response['disagreeComments']), 0);

        $vote->position = -1;
        $vote->save();


        $this->get('/api/motion/'.$vote->motion_id.'/comment')
            ->assertResponseStatus(200);


        $response = json_decode($this->response->getContent(), true);
        $this->assertEquals(count($response['agreeComments']), 0);
        $this->assertEquals(count($response['disagreeComments']), 1);
    }
}
