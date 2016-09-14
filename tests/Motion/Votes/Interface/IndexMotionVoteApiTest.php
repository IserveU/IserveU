<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class IndexMotionVoteApiTest extends TestCase
{
    use DatabaseTransactions;    


    protected static $motion;

    public function setUp()
    {
        parent::setUp();


        if(is_null(static::$motion)){
            static::$motion =   factory(App\Motion::class,'published')->create();

            $votes   =   factory(App\Vote::class,10)->create();

            foreach($votes as $vote){
                $vote->motion_id = static::$motion->id;
                $vote->save();
            }
        }
    }


    ///////////////////////////////////////////////////////////CORRECT RESPONSES 

    /** @test */
    public function default_motion_vote_filter(){
       
        $this->get("/api/motion/".static::$motion->id."/vote")
                ->assertResponseStatus(200)
                ->seeJsonStructure([
                    'abstain' => [
                        "active"
                    ],
                    'for' => [
                        "active"
                    ],
                    'against' => [
                        "active"
                    ]
                ]);

    }

  

    /////////////////////////////////////////////////////////// INCORRECT RESPONSES
    
}
