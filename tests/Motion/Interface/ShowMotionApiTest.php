<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ShowMotionApiTest extends TestCase
{
    use DatabaseTransactions;    

    public function setUp()
    {
        parent::setUp();
    }

    
    /////////////////////////////////////////////////////////// CORRECT RESPONSES
   
    /** @test */
    public function show_motion_test(){
        $this->signInAsRole('administrator');

        $motion = factory(App\Motion::class)->create();


        $this->visit("/api/motion/".$motion->id)
            ->seeJsonStructure([
                'title','text','summary','department_id','id','motionOpenForVoting','closing','userVote','status','updated_at'
            ])->dontSeeJson([
                'votes','users'
            ]);

    }

}
