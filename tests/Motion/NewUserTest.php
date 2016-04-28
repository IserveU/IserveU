<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class NewUserTest extends TestCase
{
   // use DatabaseTransactions;
    use WithoutMiddleware;

    public function setUp(){
        parent::setUp();
        
        $this->signIn();
    }

    /** @test */
    public function it_can_be_created()
    {
        $faker = Faker\Factory::create();

        $user = [ 'first_name'        => $faker->firstName,
                  'middle_name'       => $faker->name,
                  'last_name'         => $faker->lastName,
                  'email'             => $faker->email,
                  'password'          => str_random(10)
                ];

        $this->expectsEvents(App\Events\User\UserCreated::class);

        $this->call('POST', '/api/user', $user);


        $this->assertResponseOk();

        $this->seeInDatabase( 'users', [ 'email' => $user['email'] ] );
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

    /** @test */
    public function it_cannot_see_a_private_users_details()
    {
        $user = factory(App\User::class, 'private')->create();


        $response = $this->call('GET', '/api/user/'.$user->id);

        $this->assertEquals(403, $response->status());
    }

    /** @test */
    public function it_cannot_update_another_users_details()
    {
        $user = factory(App\User::class, 'private')->create();

        $updateData = ['first_name' => 'updated_first_name', 
                       'last_name'  => 'updated_last_name'];

        $response = $this->call('PATCH', '/api/user/'.$user->id, $updateData);

        $this->assertEquals(403, $response->status());
    }

    /** @test */
    public function it_can_see_a_public_users_details()
    {
        $user = factory(App\User::class,'public')->create();

        $this->call('GET', '/api/user/'.$user->id);


        $this->assertResponseOk();

        $this->seeJson(['id' => $user->id, 'first_name' => $user->first_name]);
    }

    /** @test */
    public function it_can_see_its_own_details()
    {
        $user = $this->user;

        $response = $this->call('GET', '/api/user/'.$user->id);

        $this->assertResponseOk();

        $this->seeJson([ 'id' => $user->id, 'email' => $user->email ]);
    }

    /** @test */
    public function it_can_update_its_own_details()
    {
        $user = $this->user;

        $updateData = ['first_name' => 'updated_first_name', 
                       'last_name'  => 'updated_last_name'];

        $this->call('PATCH', '/api/user/'.$user->id, $updateData);

        $this->assertResponseOk();

        $this->seeJson(array_merge($updateData, ['id' => $user->id]));

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
