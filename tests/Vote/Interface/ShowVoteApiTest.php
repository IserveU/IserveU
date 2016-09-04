<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ShowVoteApiTest extends TestCase
{
    use DatabaseTransactions;    



    public function setUp()
    {

        parent::setUp();
    }

    
    /////////////////////////////////////////////////////////// CORRECT RESPONSES
   
    /** @test */
    public function show_vote_test(){
        $this->signInAsRole('administrator');

        $vote = factory(App\Vote::class)->create();


        $this->visit("/api/vote/".$vote->id)
            ->seeJsonStructure([
                'id','position','motion_id','user_id','deferred_to_id'
            ])->dontSeeJson([
              
            ]);

    }

}
