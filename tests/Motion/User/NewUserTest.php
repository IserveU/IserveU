<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class NewUserTest extends TestCase
{
    use DatabaseTransactions;
    use WithoutMiddleware;

    public function setUp(){
        parent::setUp();
        
        $this->signIn();
    }


    /** @test */
    public function motion_index_permissions_working()
    {
        $motions = generateMotions($this);

        //Default with no filters
        filterCheck(
                $this,
                [
                    $motions['motionPublished'],
                    $motions['motionMyPublished'],
                    $motions['motionClosed'],
                    $motions['motionMyClosed']
                ],[
                    $motions['motionDraft'],
                    $motions['motionMyDraft'],
                    $motions['motionReview'],
                    $motions['motionMyReview']
                ]
        );
        $this->assertResponseStatus(200);

        //Filter to see published
        filterCheck(
                $this,
                [
                    $motions['motionPublished'],
                    $motions['motionMyPublished'],
                ],[
                    $motions['motionClosed'],
                    $motions['motionMyClosed'],
                    $motions['motionDraft'],
                    $motions['motionMyDraft'],
                    $motions['motionReview'],
                    $motions['motionMyReview']
                ],
                ['status'=>[2]]
        );
        $this->assertResponseStatus(200);
   
       //Filter to see my drafts
        filterCheck(
                $this,
                [
                    $motions['motionMyDraft'],
                    $motions['motionMyReview']
                ],[
                    $motions['motionPublished'],
                    $motions['motionMyPublished'],
                    $motions['motionClosed'],
                    $motions['motionMyClosed'],
                    $motions['motionDraft'],
                    $motions['motionReview']
                ],
                ['status'=>[0,1]]
        );
        $this->assertResponseStatus(200);

      
        //Filter to see published
        filterCheck(
                $this,
                [
                    $motions['motionMyDraft'],
                ],[
                    $motions['motionMyReview'],
                    $motions['motionPublished'],
                    $motions['motionMyPublished'],
                    $motions['motionClosed'],
                    $motions['motionMyClosed'],
                    $motions['motionDraft'],
                    $motions['motionReview']
                ],
                ['status'=>[0]]
        );
        $this->assertResponseStatus(200);    
    }


    /** @test */
    public function it_can_see_a_closed_motion()
    {
        $motion = factory(App\Motion::class,'closed')->create();

        $response = $this->call('GET', '/api/motion/'.$motion->id);

        $this->assertResponseOk();

        $this->seeJson( [ 'id' => $motion->id, 'text' => $motion->text ] );
    }

    /** @test */
    public function it_cannot_see_an_unpublished_motion()
    {
        $motion = factory(App\Motion::class, 'draft')->create();

        $response = $this->call('GET', '/api/motion/'.$motion->id);


        $this->assertEquals(403, $response->status());
    }

    /** @test */
    public function it_can_see_comments_made_on_the_motion()
    {
        $motion = factory(App\Motion::class, 'published')->create();

        $this->call('GET', '/api/motion/'.$motion->id.'/comment');

        $this->assertResponseOk();
    }

    /** @test */
    public function it_can_see_the_total_votes_of_the_motion()
    {
        $motion = factory(App\Motion::class, 'published')->create();

        $this->call('GET', '/api/motion/'.$motion->id.'/vote');

        $this->assertResponseOk();
    }

    /** @test */
    public function it_cannot_create_a_motion()
    {
        $draft = factory(App\Motion::class, 'draft')->make()->toArray();
        $review = factory(App\Motion::class, 'review')->make()->toArray();

        $response = $this->call('POST', '/api/motion', $draft);
        $this->assertEquals(403, $response->status());

        $response = $this->call('POST', '/api/motion', $review);
        $this->assertEquals(403, $response->status());
    }

    /** @test */
    public function it_cannot_create_a_vote()
    {
        $motion = factory(App\Motion::class, 'published')->create();

        $vote = ['position'  => 1, 
                 'motion_id' => $motion->id];

        $response = $this->call('POST', '/api/vote', $vote);

        $this->assertEquals(401, $response->status());
    }

    /** @test */
    public function it_cannot_create_a_comment()
    {
        $faker = Faker\Factory::create();

        $motion = factory(App\Motion::class, 'published')->create();

        $comment = ['vote_id'  => 1, 
                    'motion_id' => $motion->id];

        $response = $this->call('POST', '/api/comment', $comment);

        $this->assertEquals(401, $response->status());
    }


    /*****************************************************************
    *
    *                          For Ike:
    *
    *   - fulfill the conditions of the function names and anything else
    *     you can think of!
    *
    *
    ******************************************************************/

    // /** @test */
    // public function it_cannot_create_a_motion_file()
    // {

    // }

    // /** @test */
    // public function it_cannot_assign_permissions_or_roles()
    // {

    // }

    // /** @test */
    // public function it_cannot_upload_a_background_image()
    // {

    // }

    // /** @test */
    // public function it_cannot_update_the_deparments()
    // {

    // }

    // /** @test */
    // public function it_cannot_update_the_ethnic_origins()
    // {

    // }


}
