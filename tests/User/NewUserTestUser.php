<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class NewUserTestUser extends TestCase
{


 use DatabaseTransactions;    
    use WithoutMiddleware;

    public function setUp()
    {
        parent::setUp();

        $this->signIn();
    }

    /*****************************************************************
    *
    *                   Basic CRUD functions:
    *
    ******************************************************************/

    /** @test **/
    public function show_public_user(){
        $user = factory(App\User::class,'public')->create();

        $this->get('/api/user/'.$user->id);

        $this->assertResponseStatus(200);

        $this->see($user->first_name);
        $this->see($user->last_name);
        $this->dontSee($user->street_name);
    }

    /** @test **/
    public function show_private_user(){
        $user = factory(App\User::class,'private')->create();

        $this->get('/api/user/'.$user->id);

        $this->assertResponseStatus(403);
        $this->dontSee($user->first_name);
        $this->dontSee($user->last_name);
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

}
