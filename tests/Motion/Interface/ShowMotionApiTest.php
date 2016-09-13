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
        // dd(\App\Department::first()->motion);
        // dd($motion->departmentRelation()->get());
        $this->visit("/api/motion/".$motion->id)
            ->seeJsonStructure([
                'title','text','summary','id','motionOpenForVoting','closing','userVote','status','updated_at','department'
            ])
            //->see($motion->departmentRelation->name)
            ->dontSeeJson([
                'votes','users'
            ]);

    }

}
