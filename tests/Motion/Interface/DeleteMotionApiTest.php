<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DeleteMotionApiTest extends TestCase
{
    use WithoutMiddleware;

    public function setUp()
    {
        parent::setUp();
    }

    /////////////////////////////////////////////////////////// CORRECT RESPONSES
   
    /** @test  ******************/
    public function delete_motion_correct_response(){
        $this->signIn();

        $this->delete("/api/motion/".$this->motion->id)
            ->assertResponseStatus(200);

        
    }

    /////////////////////////////////////////////////////////// INCORRECT RESPONSES
    
}
