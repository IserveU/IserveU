<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MotionDatabase extends TestCase
{
    // /**
    //  * A basic test example.
    //  *
    //  * @return void
    //  */
    // public function make_range_of_motions()
    // {
    //     $motionDraft = factory(App\Motion::class,'draft')->create();
        
    //     $motionReview = factory(App\Motion::class,'review')->create();
       
    //     $motionPublished = factory(App\Motion::class,'published')->create();
       
    //     $motionClosed = factory(App\Motion::class,'closed')->create();
      
    // }


     /** @test  */
    public function status_scope_get_motions_with_a_status()
    {
        $motionDraft = factory(App\Motion::class,'draft')->create();
        $motionReview = factory(App\Motion::class,'review')->create();     
        $motionPublished = factory(App\Motion::class,'published')->create();
        $motionClosed = factory(App\Motion::class,'closed')->create();

        $motion = \App\Motion::status(0)->first();
        $this->assertEquals($motion->status,0);

        $motion = \App\Motion::status(1)->first();
        $this->assertEquals($motion->status,1);

        $motion = \App\Motion::status(2)->first();
        $this->assertEquals($motion->status,2);

        $motion = \App\Motion::status(3)->first();
        $this->assertEquals($motion->status,3);
    }

     /** @test  */
    public function status_scope_get_motions_with_many_status()
    {
        $motionDraft = factory(App\Motion::class,'draft')->create();
        $motionReview = factory(App\Motion::class,'review')->create();     
        $motionPublished = factory(App\Motion::class,'published')->create();
        $motionClosed = factory(App\Motion::class,'closed')->create();

        $motion = \App\Motion::status([0,1,2])->first();
        $this->assertNotEquals($motion->status,3);

        $motion = \App\Motion::status([1,2,3])->first();
        $this->assertNotEquals($motion->status,0);
 
        $motion = \App\Motion::status([2,3])->first();
        $this->assertNotEquals($motion->status,0);
        $this->assertNotEquals($motion->status,1);
    }
}
