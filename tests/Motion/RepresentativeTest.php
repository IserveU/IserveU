<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RepresentativeTest extends TestCase
{

    // use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->published_motion = factory(App\Motion::class, 'published')->create();

        $this->signIn();

        $this->user->addUserRoleByName('representative');
    }

    /*****************************************************************
    *
    *                   Basic CRUD functions:
    *
    ******************************************************************/

    /** @test */
    public function it_can_see_a_motion()
    {
        $motion = createPublishedMotion();

        $this->call('GET', '/api/motion/'.$motion->id); // , ['token' => $this->token]

        $this->assertResponseOk();
        $this->seeJson([ 'id' => $motion->id, 'text' => $motion->text ]);
    }

    /** @test */
    public function it_can_create_a_motion()
    {
        $motion = postMotion($this);
    
        $this->seeInDatabase('motions', ['id' => $motion->id, 'title' => $motion->title, 'summary' => $motion->summary]);

        $this->call('GET', '/api/motion/'.$motion->id, ['token' => $this->token]);
        
        $this->assertResponseOk();
    }

    /** @test */
    public function it_can_create_a_vote()
    {
        $vote = postVote($this);
     
        $this->seeInDatabase('votes', ['id' => $vote->id, 'position' => $vote->position, 'user_id' => $this->user->id]);
    }

    /** @test */ 
    public function it_can_create_a_comment()
    {
        $comment = postComment($this);

        $this->seeInDatabase('comments', [ 'id' => $comment->id, 'text' => $comment->text ]);
    }

    /** @test */
    public function it_can_create_a_comment_vote()
    {
        $comment_vote = postCommentVote($this);

        $this->seeInDatabase('comment_votes', [ 'id' => $comment_vote->id, 'position' => $comment_vote->position ]);
    }

    /** @test */
    public function it_can_update_a_motion()
    {
        $motion  = postMotion($this);
        
        // Update Motion
        $closing = createClosingDate();

        $updated = factory(App\Motion::class, 'as_this_user')->make()->toArray();
        $updated = array_merge($updated, createClosingDate() );

        $updated = $this->call('PATCH', '/api/motion/'.$motion->id.'?token='.$this->token, $updated);
        $updated = $updated->getOriginalContent();

        $this->assertResponseOk();
        $this->seeInDatabase('motions', ['title' => $updated->title, 'summary' => $updated->summary, 'closing' => $closing]);
    }

    /** @test */
    public function it_can_update_vote()
    {
        $vote = postVote($this);

        $new_position = switchVotePosition();

        // Update Vote
        $this->call('PATCH', '/api/vote/'.$vote->id.'?token='.$this->token, 
                  [ 'position' => $new_position, 'id' => $vote->id ]);
        $this->assertResponseOk();
        $this->seeInDatabase('votes', ['id' => $vote->id, 'position' => $new_position, 'user_id' => $this->user->id]);
    }

    /** @test */
    public function it_can_update_comment()
    {
        $comment = postComment($this);
        // Update comment
        $new_comment = factory(App\Comment::class)->make(['id' => $comment->id])->toArray();

        $this->call('PATCH', '/api/comment/'.$comment->id.'?token='.$this->token, $new_comment);
        $this->assertResponseOk();
        $this->seeInDatabase('comments', [ 'id' => $comment->id, 'text' => $new_comment['text'] ]);
    }

    /** @test */
    public function it_can_update_comment_vote()
    {
        $comment_vote = postCommentVote($this);

        $new_position = switchVotePosition();

        $this->call('PATCH', '/api/comment_vote/'.$comment_vote->id.'?token='.$this->token, 
                  [ 'position' => $new_position ]);
        $this->assertResponseOk();
        $this->seeInDatabase('comment_votes', ['id' => $comment_vote->id, 'position' => $new_position]);
    }

    /** @test */
    public function it_can_delete_a_motion()
    {
        $motion  = postMotion($this);
        
        // Delete Motion
        $response = $this->call('DELETE', '/api/motion/'.$motion->id.'?token='.$this->token);
        $this->assertResponseOk();
        $this->seeInDatabase('motions', ['deleted_at' => $motion->deleted_at]);
    }

  /** @test */
    public function it_can_restore_a_motion()
    {
        $motion  = postMotion($this);
        
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
        
        $vote = postVote($this);
        
        // Delete Vote
        $this->call('DELETE', '/api/vote/'.$vote->id.'?token='.$this->token);
        $this->assertResponseOk();
        $this->seeInDatabase('votes', ['id' => $vote->id, 'position' => 0, 'user_id' => $this->user->id]);

    }


        /** @test */
    public function it_can_delete_comment()
    {
        $comment = postComment($this);
        
        // Delete comment
        $delete = $this->call('DELETE', '/api/comment/'.$comment->id.'?token='.$this->token);
        $delete = $delete->getOriginalContent();

        $this->assertResponseOk();
        $this->seeInDatabase('comments', ['deleted_at' => $delete->deleted_at]);
    }

        /** @test */
    public function it_can_delete_comment_vote()
    {
        $comment_vote = postCommentVote($this);
        
        $this->call('DELETE', '/api/comment_vote/'.$comment_vote->id.'?token='.$this->token);
        $this->assertResponseOk();
        $this->notSeeInDatabase('comment_votes', ['id' => $comment_vote->id]);
    }



    /*****************************************************************
    *
    *                          For Ike:
    *  - be able to switch the status of a motion from 'draft' to 'published', etc.
    *  - be able to do everything to motions
    *  - write a function that tests overall votes of a motion with the representatives deferred votes; should return a complex
    *    multidimensional array. This is something you may need to create many factory users submitting multiple votes
    *    with the deferrals involved. 
    * 
    *    Negative tests (The above tests are meant to pass, expect a typical response. They are higher priority than negative ones atm.):
    *  - unable to CRUD users (read: for private users only)
    *  - unable to CRUD comments/votes (note: representatives can read)
    *  - unable to CRUD background images
    *
    ******************************************************************/


}
