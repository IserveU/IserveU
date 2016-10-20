<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class CitizenUserTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
        $this->setSettings(['security.verify_citizens' => 1]);

        $this->signInAsRole('citizen');
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

        $this->get('/api/user/'.$user->id);

        $this->assertResponseStatus(200);

        $this->see($user->first_name);
        $this->see($user->last_name);
        $this->dontSee($user->street_name);
    }

    /** @test **/
    public function show_private_user()
    {
        $user = factory(App\User::class, 'private')->create();

        $this->get('/api/user/'.$user->id);

        $this->assertResponseStatus(200); //Will be a 403 one day
        $this->dontSee($user->first_name);
        $this->dontSee($user->last_name);
    }
}
