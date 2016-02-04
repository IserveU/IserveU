<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AdministratorTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->published_motion = factory(App\Motion::class, 'published')->create();

        $this->signIn();
        $this->user->addUserRoleByName('administrator');
    }

    /*****************************************************************
    *
    *                   Basic CRUD functions:
    *
    ******************************************************************/

    /** @test */
    public function it_can_create_a_motion()
    {
        $motion = factory(App\Motion::class, 'as_this_user')->make()->toArray();

        $closing = new DateTime();
        $closing->add(new DateInterval('P7D'));
        
        $motion = array_merge($motion, ['closing' => $closing]);
        $motion = $this->call('POST', '/api/motion?token='.$this->token, $motion);
        $motion = $motion->getOriginalContent();

        $this->assertResponseOk();
        $this->seeInDatabase('motions', ['title' => $motion->title, 'summary' => $motion->summary, 'closing' => $closing]);
    }

    /** @test */
    public function it_can_create_a_vote()
    {
        $motion = $this->published_motion;

        $vote = factory(App\Vote::class)->make(['motion_id' => $motion->id])->toArray();

        $this->call('POST', '/api/vote?token='.$this->token, $vote);
        $this->assertResponseOk();
        $this->seeInDatabase('votes', ['motion_id' => $motion->id, 'position' => $vote['position'], 'user_id' => $this->user->id]);
    }

    /** @test */ 
    public function it_can_create_a_comment()
    {
        $motion = $this->published_motion;

        // Make a vote
        $vote = factory(App\Vote::class)->make(['motion_id' => $motion->id])->toArray();
        $vote = $this->call('POST', '/api/vote?token='.$this->token, $vote);
        $vote = $vote->getOriginalContent();
        
        $this->assertResponseOk();

        // Make a comment
        $comment = factory(App\Comment::class)->make()->toArray();
        $comment = array_merge($comment, ['vote_id' => $vote->id]);

        $this->call('POST', '/api/comment?token='.$this->token, $comment);
        $this->assertResponseOk();
        $this->seeInDatabase('comments', [ 'vote_id' => $vote->id, 'text' => $comment['text'] ]);
    }

    /** @test */
    public function it_can_create_a_comment_vote()
    {
        $motion = $this->published_motion;

        // Make a vote
        $vote = factory(App\Vote::class)->make(['motion_id' => $motion->id])->toArray();
        $vote = $this->call('POST', '/api/vote?token='.$this->token, $vote);
        $vote = $vote->getOriginalContent();
        
        // Make a comment
        $comment = factory(App\Comment::class)->make()->toArray();
        $comment = array_merge($comment, ['vote_id' => $vote->id]);
        $comment = $this->call('POST', '/api/comment?token='.$this->token, $comment);
        $comment = $comment->getOriginalContent();

        $this->assertResponseOk();
        $this->seeInDatabase('comments', [ 'vote_id' => $vote->id, 'text' => $comment->text ]);

        // Make a comment vote
        $comment_vote = factory(App\CommentVote::class)->make(['comment_id' => $comment->id, 'vote_id' => $vote->id])->toArray();
        
        $this->call('POST', '/api/comment_vote?token='.$this->token, $comment_vote);
        $this->assertResponseOk();
        $this->seeInDatabase('comment_votes', [ 'comment_id' => $comment->id, 'vote_id' => $vote->id, 'position' => $comment_vote['position']  ]);

    }

    /** @test */
    public function it_can_update_a_motion()
    {
        $motion = factory(App\Motion::class, 'as_this_user')->make()->toArray();
        $closing = new DateTime();
        $closing->add(new DateInterval('P7D'));
        
        // Make motion
        $motion = array_merge($motion, ['closing' => $closing]);
        $motion = $this->call('POST', '/api/motion?token='.$this->token, $motion);
        $motion = $motion->getOriginalContent();

        $this->assertResponseOk();
        $this->seeInDatabase('motions', ['title' => $motion->title, 'summary' => $motion->summary,  'closing' => $closing]);

        // Update Motion

        $updated = factory(App\Motion::class, 'as_this_user')->make()->toArray();
        $updated = array_merge($updated, ['closing' => $closing]);
        $updated = $this->call('PATCH', '/api/motion/'.$motion->id.'?token='.$this->token, $updated);
        $updated = $updated->getOriginalContent();

        $this->assertResponseOk();
        $this->seeInDatabase('motions', ['title' => $updated->title, 'summary' => $updated->summary, 'closing' => $closing]);
    }

    /** @test */
    public function it_can_update_vote()
    {
        $faker  = Faker\Factory::create();
        $motion = $this->published_motion;

        // Make a vote
        $vote = factory(App\Vote::class)->make(['motion_id' => $motion->id])->toArray();
        $vote = $this->call('POST', '/api/vote?token='.$this->token, $vote);
        $vote = $vote->getOriginalContent();

        // Switch vote
        $new_position = $faker->shuffle(array(-1, 0, 1));
        $new_position = $new_position[$faker->numberBetween($min = 0, $max = 2)];

        // Update Vote
        $this->call('PATCH', '/api/vote/'.$vote->id.'?token='.$this->token, 
                  [ 'position' => $new_position, 'id' => $vote->id ]);
        $this->assertResponseOk();
        $this->seeInDatabase('votes', ['motion_id' => $motion->id, 'position' => $new_position, 'user_id' => $this->user->id]);
    }

    /** @test */
    public function it_can_update_comment()
    {
        $motion = $this->published_motion;

        // Make a vote
        $vote = factory(App\Vote::class)->make(['motion_id' => $motion->id])->toArray();
        $vote = $this->call('POST', '/api/vote?token='.$this->token, $vote);
        $vote = $vote->getOriginalContent();

        // Make a comment
        $comment = factory(App\Comment::class)->make()->toArray();
        $comment = array_merge($comment, ['vote_id' => $vote->id]);
        $comment = $this->call('POST', '/api/comment?token='.$this->token, $comment);
        $comment = $comment->getOriginalContent();

        // Update comment
        $new_comment = factory(App\Comment::class)->make(['id' => $comment->id])->toArray();

        $this->call('PATCH', '/api/comment/'.$comment->id.'?token='.$this->token, $new_comment);
        $this->assertResponseOk();
        $this->seeInDatabase('comments', [ 'id' => $comment->id, 'text' => $new_comment['text'] ]);
    }

    /** @test */
    public function it_can_update_comment_vote()
    {
        $faker  = Faker\Factory::create();
        $motion = $this->published_motion;

        // Make a vote
        $vote = factory(App\Vote::class)->make(['motion_id' => $motion->id])->toArray();
        $vote = $this->call('POST', '/api/vote?token='.$this->token, $vote);
        $vote = $vote->getOriginalContent();

        // Make a comment
        $comment = factory(App\Comment::class)->make()->toArray();
        $comment = array_merge($comment, ['vote_id' => $vote->id]);
        $comment = $this->call('POST', '/api/comment?token='.$this->token, $comment);
        $comment = $comment->getOriginalContent();

        // Make a comment vote
        $comment_vote = factory(App\CommentVote::class)->make(['comment_id' => $comment->id, 'vote_id' => $vote->id])->toArray();
        $comment_vote = $this->call('POST', '/api/comment_vote?token='.$this->token, $comment_vote);
        $comment_vote = $comment_vote->getOriginalContent();

        // Update comment vote
        $new_position = $faker->shuffle(array(-1, 0, 1));
        $new_position = $new_position[$faker->numberBetween($min = 0, $max = 2)];

        $this->call('PATCH', '/api/comment_vote/'.$comment_vote->id.'?token='.$this->token, 
                  [ 'position' => $new_position, 'id' => $vote->id ]);
        $this->assertResponseOk();
        $this->seeInDatabase('comment_votes', ['position' => $new_position, 'comment_id' => $comment->id]);

    }

    /** @test */
    public function it_can_delete_a_motion()
    {
        $motion = factory(App\Motion::class, 'as_this_user')->make()->toArray();

        $closing = new DateTime();
        $closing->add(new DateInterval('P7D'));
        
        // Make motion
        $motion = array_merge($motion, ['closing' => $closing]);
        $motion = $this->call('POST', '/api/motion?token='.$this->token, $motion);
        $motion = $motion->getOriginalContent();

        $this->assertResponseOk();
        $this->seeInDatabase('motions', ['title' => $motion->title, 'summary' => $motion->summary, 'closing' => $closing]);

        // Delete Motion
        $response = $this->call('DELETE', '/api/motion/'.$motion->id.'?token='.$this->token);
        $this->assertResponseOk();
        $this->seeInDatabase('motions', ['deleted_at' => $motion->deleted_at]);
    }

  /** @test */
    public function it_can_restore_a_motion()
    {
        $motion = factory(App\Motion::class, 'as_this_user')->make()->toArray();

        $closing = new DateTime();
        $closing->add(new DateInterval('P7D'));
        
        // Make motion
        $motion = array_merge($motion, ['closing' => $closing]);
        $motion = $this->call('POST', '/api/motion?token='.$this->token, $motion);
        $motion = $motion->getOriginalContent();

        $this->assertResponseOk();
        $this->seeInDatabase('motions', ['title' => $motion->title, 'summary' => $motion->summary, 'closing' => $closing]);

        // Delete Motion
        $this->call('DELETE', '/api/motion/'.$motion->id.'?token='.$this->token);
        $this->assertResponseOk();
        $this->seeInDatabase('motions', ['deleted_at' => $motion->deleted_at]);

        // Restore motion
        $this->call('GET', '/api/motion/'.$motion->id.'/restore?token='.$this->token);
        $this->assertResponseOk();
        $this->seeInDatabase('motions', ['deleted_at' => null]);
    }

    /** @test */
    public function it_can_delete_vote()
    {
        // As per the API delete route, you cannot delete a vote, you may only switch to abstain.
        $motion = $this->published_motion;

        // Create Vote
        $vote = factory(App\Vote::class)->make(['motion_id' => $motion->id])->toArray();
        $vote = $this->call('POST', '/api/vote?token='.$this->token, $vote);
        $vote = $vote->getOriginalContent();

        $this->assertResponseOk();
        $this->seeInDatabase('votes', ['motion_id' => $motion->id, 'position' => $vote->position, 'user_id' => $this->user->id]);

        // Delete Vote
        $this->call('DELETE', '/api/vote/'.$vote->id.'?token='.$this->token);
        $this->assertResponseOk();
        $this->seeInDatabase('votes', ['motion_id' => $motion->id, 'position' => 0, 'user_id' => $this->user->id]);

    }

        /** @test */
    public function it_can_delete_comment()
    {
        $motion = $this->published_motion;

        // Make a vote
        $vote = factory(App\Vote::class)->make(['motion_id' => $motion->id])->toArray();
        $vote = $this->call('POST', '/api/vote?token='.$this->token, $vote);
        $vote = $vote->getOriginalContent();
        
        $this->assertResponseOk();

        // Make a comment
        $comment = factory(App\Comment::class)->make()->toArray();
        $comment = array_merge($comment, ['vote_id' => $vote->id]);
        $comment = $this->call('POST', '/api/comment?token='.$this->token, $comment);
        $comment = $comment->getOriginalContent();

        $this->assertResponseOk();
        $this->seeInDatabase('comments', [ 'vote_id' => $vote->id, 'text' => $comment['text'] ]);

        // Delete comment
        $delete = $this->call('DELETE', '/api/comment/'.$comment->id.'?token='.$this->token);
        $delete = $delete->getOriginalContent();

        $this->assertResponseOk();
        $this->seeInDatabase('comments', ['deleted_at' => $delete->deleted_at]);
    }

        /** @test */
    public function it_can_delete_comment_vote()
    {
        $motion = $this->published_motion;

        // Make a vote
        $vote = factory(App\Vote::class)->make(['motion_id' => $motion->id])->toArray();
        $vote = $this->call('POST', '/api/vote?token='.$this->token, $vote);
        $vote = $vote->getOriginalContent();
        
        $this->assertResponseOk();

        // Make a comment
        $comment = factory(App\Comment::class)->make()->toArray();
        $comment = array_merge($comment, ['vote_id' => $vote->id]);
        $comment = $this->call('POST', '/api/comment?token='.$this->token, $comment);
        $comment = $comment->getOriginalContent();

        $this->assertResponseOk();
        $this->seeInDatabase('comments', [ 'vote_id' => $vote->id, 'text' => $comment['text'] ]);

        // Make a comment vote
        $comment_vote = factory(App\CommentVote::class)->make(['comment_id' => $comment->id, 'vote_id' => $vote->id])->toArray();
        $comment_vote = $this->call('POST', '/api/comment_vote?token='.$this->token, $comment_vote);
        $comment_vote = $comment_vote->getOriginalContent();

        $this->call('DELETE', '/api/comment_vote/'.$comment_vote->id.'?token='.$this->token);
        $this->assertResponseOk();
        $this->notSeeInDatabase('comment_votes', ['id' => $comment_vote->id]);
    }




    /****************** DUPLICATE FROM COUNCILLOR TESTS ********************/



    /*****************************************************************
    *
    *                          For Ike:
    *  - replicate the CRUD functions for user details
    *  - be able to assign roles and take away roles from other users
    *  - replicate the CRUD functions for other user's votes/comments (these should fail)
    *  - be able to switch the status of a motion from 'draft' to 'published', etc.
    *
    *
    *  Note: if you complete councillor tests first, and then copy it over, it should replicate exactly
    *        what it is you need; and you would just flip the negative tests from 403/401's to 200's. Saving you time.
    *
    ******************************************************************/


}
