<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MotionCommentVotesPermissionsTest extends TestCase
{
    use DatabaseTransactions;    
    use WithoutMiddleware;

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
    

    /** @test */
    public function it_can_create_a_comment_vote()
    {

        $comment_vote = postCommentVote($this);

        $this->seeInDatabase('comment_votes', [ 'id' => $comment_vote->id, 'position' => $comment_vote->position ]);
    }


    /** @test */
    public function it_can_update_comment_vote()
    {
        $comment_vote = postCommentVote($this);

        $new_position = switchVotePosition();

        $this->call('PATCH', '/api/comment_vote/'.$comment_vote->id, 
                  [ 'position' => $new_position ]);
        $this->assertResponseOk();
        $this->seeInDatabase('comment_votes', ['id' => $comment_vote->id, 'position' => $new_position]);
    }


        /** @ test */
    public function it_can_delete_comment_vote()
    {
        $comment_vote = postCommentVote($this);
        
        $this->call('DELETE', '/api/comment_vote/'.$comment_vote->id);
        $this->assertResponseOk();
        $this->notSeeInDatabase('comment_votes', ['id' => $comment_vote->id]);
    }




}
