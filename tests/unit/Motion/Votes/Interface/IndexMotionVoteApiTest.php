<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class IndexMotionVoteApiTest extends TestCase
{
    use DatabaseTransactions;    



    public function setUp()
    {
        parent::setUp();


      
    }


    ///////////////////////////////////////////////////////////CORRECT RESPONSES 

    /** @test */
    public function default_motion_vote_filter(){
        $motion = $this->getStaticMotion();
       
        $this->get("/api/motion/".$motion->id."/vote")
                ->assertResponseStatus(200)
                ->seeJsonStructure([
                    'abstain' => [
                        "active"
                    ],
                    'agree' => [
                        "active"
                    ],
                    'disagree' => [
                        "active"
                    ]
                ]);

    }

  

    /////////////////////////////////////////////////////////// INCORRECT RESPONSES
    
}
