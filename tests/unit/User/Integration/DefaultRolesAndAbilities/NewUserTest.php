<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class NewUserTest extends BrowserKitTestCase
{
    use DatabaseTransactions;

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
    public function show_public_user()
    {
        $user = factory(App\User::class, 'public')->create();

        $this->get('/api/user/'.$user->slug);

        $this->assertResponseStatus(200);

        $this->see($user->first_name);
        $this->see($user->last_name);
        $this->dontSee($user->street_name);
    }

    /** @test **/
    public function show_private_user()
    {
        $user = factory(App\User::class, 'private')->create();

        $this->get('/api/user/'.$user->slug);

        $this->assertResponseStatus(200);   //Will be a 403 one day
        $this->dontSee($user->first_name);
        $this->dontSee($user->last_name);
    }

    /** @test */
    public function it_cannot_update_another_users_details()
    {
        $user = factory(App\User::class, 'private')->create();

        $updateData = ['first_name' => 'updated_first_name',
                       'last_name'  => 'updated_last_name', ];

        $response = $this->call('PATCH', '/api/user/'.$user->slug, $updateData);

        $this->assertEquals(403, $response->status());
    }

    /** @test */
    public function it_can_see_a_public_users_details()
    {
        $user = factory(App\User::class, 'public')->create();

        $this->call('GET', '/api/user/'.$user->slug);

        $this->assertResponseOk();

        $this->seeJson(['id' => $user->id, 'first_name' => $user->first_name]);
    }

    /** @test */
    public function it_can_see_its_own_details()
    {
        $user = $this->user;

        $response = $this->call('GET', '/api/user/'.$user->slug);

        $this->assertResponseOk();

        $this->seeJson(['id' => $user->id, 'email' => $user->email]);
    }

    /** @test */
    public function it_can_update_its_own_details()
    {
        $this->user;

        $updateData = [
            'first_name' => 'Ufirst',
            'last_name'  => 'Ulast',
        ];

        $this->patch('/api/user/'.$this->user->slug, $updateData);

        $this->assertResponseOk();
        $this->seeInDatabase('users', array_merge(['id' => $this->user->id], [
            'first_name' => 'Ufirst',
            'last_name'  => 'Ulast',
        ]));
    }
}
