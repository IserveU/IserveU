<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class VotePermissionTest extends TestCase
{
   // use DatabaseTransactions;    
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
    public function it_can_create_a_vote()
    {
        $vote = postVote($this);
     
        $this->seeInDatabase('votes', ['id' => $vote->id, 'position' => $vote->position, 'user_id' => $this->user->id]);
    }





    /** @test */
    public function it_can_update_vote()
    {
        $vote = postVote($this);

        $new_position = switchVotePosition();

        // Update Vote
        $this->call('PATCH', '/api/vote/'.$vote->id, 
                  [ 'position' => $new_position, 'id' => $vote->id ]);

        $this->assertResponseOk();
        $this->seeInDatabase('votes', ['id' => $vote->id, 'position' => $new_position, 'user_id' => $this->user->id]);
    }


    /** @test */
    public function it_can_abstain_vote()
    {
        // As per the API delete route, you cannot delete a vote, you may only switch to abstain.
        
        $vote = postVote($this);
        
        // Delete Vote
        $this->call('DELETE', '/api/vote/'.$vote->id);

        $this->assertResponseOk();
        $this->seeInDatabase('votes', ['id' => $vote->id, 'position' => 0, 'user_id' => $this->user->id]);

    }


    /** @test */
    public function it_can_see_the_total_votes_of_a_motion()
    {
        $motion = factory(App\Motion::class, 'published')->create();

        $this->call('GET', '/api/motion/'.$motion->id.'/vote');

        $this->assertResponseOk();
    }



    /** @test */
    public function it_cannot_create_a_vote()
    {
        $motion = factory(App\Motion::class, 'published')->create();

        $vote = ['position'  => 1, 
                 'motion_id' => $motion->id];

        $response = $this->call('POST', '/api/vote', $vote);

        $this->assertEquals(401, $response->status());
    }

}
