<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class VotePermissionTest extends TestCase
{
    use DatabaseTransactions;    

    public function setUp()
    {
        parent::setUp();

        $this->class         =   App\Vote::class;
        $this->route        =   "/api/vote/";

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

        $this->signInAsPermissionedUser('create-vote');
        
        $this->motion       =   factory(App\Motion::class)->create();
        $this->route        =   "/api/motion/".$this->motion->id."/vote/";

        $this->storeFieldsGetSee(['position'],200);   

    }

    /** @test */
    public function it_can_update_own_vote()
    {

        $this->signInAsPermissionedUser('create-vote');


        $this->modelToUpdate = factory(App\Vote::class)->create([
            'user_id'   =>      $this->user->id
        ]);

        $this->updateFieldsGetSee(['position'],200);
    }


    /** @test */
    public function it_can_abstain_vote()
    {
        // As per the API delete route, you cannot delete a vote, you may only switch to abstain.
        $this->signInAsPermissionedUser('create-vote');

        $vote = factory(App\Vote::class)->create([
            'user_id'   =>  \Auth::user()->id,
            'position'  =>  1
        ]);
        
        // Delete Vote
        $this->delete('/api/vote/'.$vote->id);

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

        $this->assertEquals(403, $response->status());
    }

}
