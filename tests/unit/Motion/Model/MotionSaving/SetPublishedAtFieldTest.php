<?php

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SetPublishedAtFieldTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
    }



    /** @test **/
    public function motion_create_as_published_has_published_at_field_set()
    {
        $publishedMotion = factory(App\Motion::class,'published')->create();

        $this->seeInDatabase('motions', ['id' => $publishedMotion->id,'published_at'=>Carbon::now()]);

    }

    /** @test **/
    public function motion_updated_to_published_has_published_at_field_set()
    {
        $motion = factory(App\Motion::class,'draft')->create();
        $motion->update([
            'status'=>'published'
        ]);

        $this->seeInDatabase('motions', ['id' => $motion->id,'published_at'=>Carbon::now()]);

    }

    //Negative Tests
    
    /** @test **/
    public function draft_motions_do_not_have_published_at_field_set()
    {
        $draftMotion = factory(App\Motion::class,'draft')->create();

        $this->seeInDatabase('motions',["id"=>$draftMotion->id,'published_at'=>null]);
        
    }

    /** @test **/
    public function review_motions_do_not_have_published_at_field_set()
    {
        $reviewMotion = factory(App\Motion::class,'review')->create();

        $this->seeInDatabase('motions',["id"=>$reviewMotion->id,'published_at'=>null]);
        
    }


    /** @test **/
    public function closed_motions_do_not_have_published_at_field_set()
    {
        $closedMotion = factory(App\Motion::class,'closed')->create();

        $this->seeInDatabase('motions',["id"=>$closedMotion->id,'published_at'=>null]);
        
    }
}
