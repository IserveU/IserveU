<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class RepresentativeUserTest extends BrowserKitTestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->signIn();
        $this->user->addRole('representative');
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

        $this->assertResponseStatus(403);
        $this->dontSee($user->first_name);
        $this->dontSee($user->last_name);
    }
}
