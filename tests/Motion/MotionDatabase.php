<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MotionDatabase extends TestCase
{


    //  /** @test  */
    // public function status_scope_get_motions_with_a_status()
    // {
    //     $motionDraft = factory(App\Motion::class,'draft')->create();
    //     $motionReview = factory(App\Motion::class,'review')->create();     
    //     $motionPublished = factory(App\Motion::class,'published')->create();
    //     $motionClosed = factory(App\Motion::class,'closed')->create();

    //     $motion = \App\Motion::status(0)->first();
    //     $this->assertEquals($motion->status,0);

    //     $motion = \App\Motion::status(1)->first();
    //     $this->assertEquals($motion->status,1);

    //     $motion = \App\Motion::status(2)->first();
    //     $this->assertEquals($motion->status,2);

    //     $motion = \App\Motion::status(3)->first();
    //     $this->assertEquals($motion->status,3);
    // }

    //  /** @test  */
    // public function status_scope_get_motions_with_many_status()
    // {
    //     $motionDraft = factory(App\Motion::class,'draft')->create();
    //     $motionReview = factory(App\Motion::class,'review')->create();     
    //     $motionPublished = factory(App\Motion::class,'published')->create();
    //     $motionClosed = factory(App\Motion::class,'closed')->create();

    //     $motion = \App\Motion::status([0,1,2])->first();
    //     $this->assertNotEquals($motion->status,3);

    //     $motion = \App\Motion::status([1,2,3])->first();
    //     $this->assertNotEquals($motion->status,0);
 
    //     $motion = \App\Motion::status([2,3])->first();
    //     $this->assertNotEquals($motion->status,0);
    //     $this->assertNotEquals($motion->status,1);
    // }



     /** @test  */
    public function update_a_draft_motion_details()
    {
        $faker = \Faker\Factory::create();

        $motionDraft = factory(App\Motion::class,'draft')->create();

        $newDetails = [
            'title'         =>  $faker->word,
            'summary'       =>  $faker->sentence,
            'text'          =>  "<p>".$faker->sentence."</p>",
            'closing'       =>  \Carbon\Carbon::now()->addDays(14)
        ];

        $motionDraft->update($newDetails);
        $this->seeInDatabase('motions',$newDetails);
    }


         /** @test  */
    public function update_a_published_motion_details()
    {
        $faker = \Faker\Factory::create();

        $motion = factory(App\Motion::class,'published')->create();

        $newDetails = [
            'title'         =>  $faker->word,
            'summary'       =>  $faker->sentence,
            'text'          =>  "<p>".$faker->sentence."</p>",
            'closing'       =>  \Carbon\Carbon::now()->addDays(14)
        ];

        $motion->update($newDetails);
        $this->seeInDatabase('motions',$newDetails);

    }
}
