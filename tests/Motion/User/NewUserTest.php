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
    public function it_can_see_motion_index()
    {
        $motionDraft = factory(App\Motion::class,'draft')->create();
        $motionMyDraft = factory(App\Motion::class,'draft')->create([
            'user_id'   => $this->user->id 
        ]);
        $motionReview = factory(App\Motion::class,'review')->create();
        $motionMyReview = factory(App\Motion::class,'review')->create([
            'user_id'   => $this->user->id 
        ]);
        $motionPublished = factory(App\Motion::class,'published')->create();
        $motionMyPublished = factory(App\Motion::class,'published')->create([
            'user_id'   => $this->user->id
        ]);
        $motionClosed = factory(App\Motion::class,'closed')->create();
        $motionMyClosed = factory(App\Motion::class,'closed')->create([
            'user_id'   => $this->user->id
        ]);

        $this->call('GET', '/api/motion/');
        $this->assertResponseStatus(403);

        $this->call('GET', '/api/motion/',['status'=>0]);
        $this->assertResponseStatus(403);

        $this->call('GET', '/api/motion/',['status'=>1]);
        $this->assertResponseStatus(403);

        $this->call('GET', '/api/motion/',['status'=>2]);
        $this->assertResponseStatus(200);

        $this->call('GET', '/api/motion/',['status'=>3]);
        $this->assertResponseStatus(200);


        $this->call('GET', '/api/motion/',['limit'=>50,'status'=>0,'user_id'=>$this->user->id]);
        $this->see($motionMyDraft->title);
      
        $this->call('GET', '/api/motion/',['limit'=>50,'status'=>1,'user_id'=>$this->user->id]);     
        $this->see($motionMyReview->title);

        $this->call('GET', '/api/motion/',['limit'=>50,'status'=>2,'user_id'=>$this->user->id]);
        $this->see($motionMyPublished->title);

            //If not filtering user
            $this->call('GET', '/api/motion/',['limit'=>5000,'status'=>2]);
            $this->see($motionPublished->title);
            $this->see($motionMyPublished->title);


        $this->call('GET', '/api/motion/',['limit'=>50,'status'=>3,'user_id'=>$this->user->id]);
        $this->see($motionMyClosed->title);

        // If not filtering user
        $this->call('GET', '/api/motion/',['limit'=>5000,'status'=>3]);
            $this->see($motionClosed->title);
            $this->see($motionMyClosed->title);

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
